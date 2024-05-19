<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\EthocaAcknowledgement;
use App\Models\EthocaAlert;
use App\Models\EthocaError;
use App\Models\EthocaRequest;
use App\Models\EthocaResponse;
use App\Models\EthocaUpdate;
use Database\Factories\AcknowledgementFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    protected $confirmed_fraud_outcomes = [
        "stopped",
        "partially_stopped",
        "previously_cancelled",
        "missed",
        "notfound",
        "account_suspended",
        "in_progress",
        "shipper_contacted",
        "other"
    ];
    protected $customer_dispute_outcomes = [
        "resolved",
        "previously_refunded",
        "unresolved_dispute",
        "notfound",
        "other"

    ];
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // recived alerts with no errors
        $requests =    EthocaRequest::factory(150)
            ->has(EthocaResponse::factory([
                'major_code' => 1,
                'status' => 'success',
                'number_of_alerts' => 10,
            ])->count(1)
                ->has(EthocaAlert::factory(10)))
            ->create([
                'title' => 'Ethoca 360 Alerts Request',
                'ethoca_fun_code' => '1',
            ]);

        // recived alerts with errors
        for ($i = 0; $i < 10; $i++) {
            # code...
            EthocaError::factory([
                'model' => EthocaResponse::class,
            ])->for(EthocaResponse::factory([
                'major_code' => fake()->randomElement([2, 3, 4]),
                'status' => 'Fail',
                'number_of_alerts' => 0,
            ])->for(EthocaRequest::factory(
                [
                    'title' => 'Ethoca 360 Alerts Request',
                    'ethoca_fun_code' => '1',
                ]
            )))->create();
        }
        $ack_alerts = collect([]);
        // ack alerts
        foreach ($requests as $request) {
            $ack_req = EthocaRequest::factory()->create([
                'title' => 'Acknowledge Ethoca Alerts Request',
                'ethoca_fun_code' => '2',
            ]);
            $ack_res = EthocaResponse::factory([
                'major_code' => 1,
                'status' => 'success',
                'ethoca_request_id' => $ack_req->id,
            ])->create();
            $alerts = $request->alerts;
            foreach ($alerts as $alert) {
                $status = fake()->randomElement(['success', 'retry', 'fail']);
                if ($status != 'success') {
                    EthocaError::factory([
                        'model' => EthocaAcknowledgement::class,
                    ])->for(EthocaAcknowledgement::factory([
                        'ethoca_alert_id' => $alert->id,
                        'ethoca_request_id' => $ack_req->id,
                        'ethoca_id' => $alert->ethoca_id,
                        'status' => $status,
                    ])->for($ack_res))->create();
                } else {
                    EthocaAcknowledgement::factory([
                        'ethoca_alert_id' => $alert->id,
                        'ethoca_request_id' => $ack_req->id,
                        'ethoca_id' => $alert->ethoca_id,
                        'status' => $status,
                    ])->for($ack_res)->create();
                    $alert->is_ack = true;
                    $alert->save();
                    $ack_alerts->push($alert);
                }
            }
        }

        // update alerts
        // $alerts_arrays = $request->alerts->get()->where('is_ack', true)->chunk(4);
        foreach ($ack_alerts->chunk(4) as $alerts_array) {
            $update_req = EthocaRequest::factory()->create([
                'title' => 'Update Ethoca Alerts Request',
                'ethoca_fun_code' => '3',
            ]);
            $success = fake()->boolean();
            $update_res = EthocaResponse::factory([
                'major_code' => $success,
                'status' => $success ? 'success' : 'fail',
                'ethoca_request_id' => $update_req->id,
            ])->create();
            if ($success) {
                foreach ($alerts_array as $alert) {
                    $status_bool = fake()->boolean();
                    // $status = fake()->randomElement(['success', 'failed']);
                    $outcomes_array = $alert->type == 'issuer_alert' ? $this->confirmed_fraud_outcomes : $this->customer_dispute_outcomes;
                    $outcome = fake()->randomElement($outcomes_array);
                    if (!$status_bool) {
                        EthocaError::factory([
                            'model' => EthocaUpdate::class,
                        ])->for(EthocaUpdate::factory([
                            'ethoca_alert_id' => $alert->id,
                            'ethoca_request_id' => $update_req->id,
                            'ethoca_id' => $alert->ethoca_id,
                            'status' => false,
                            'outcome' => $outcome
                        ])->for($update_res))->create();
                    } else {
                        EthocaUpdate::factory([
                            'ethoca_alert_id' => $alert->id,
                            'ethoca_request_id' => $update_req->id,
                            'ethoca_id' => $alert->ethoca_id,
                            'status' => true,
                            'outcome' => $outcome
                        ])->for($update_res)->create();
                        $alert->is_updated = true;
                        $alert->save();
                    }
                }
            }else{
                EthocaError::factory([
                    'model' => EthocaResponse::class,
                ])->for($update_res)->create();
            }
        }
    }
}
