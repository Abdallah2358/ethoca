<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\CrmAction;
use App\Models\CrmTransaction;
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
        $csvFile = database_path('seeders/raw_data.csv');

        // Open the CSV file
        if (($handle = fopen($csvFile, 'r')) !== FALSE) {
            // Get the headers from the first row
            $headers = fgetcsv($handle, 1000, ',');

            // Process each row of the CSV file
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                // Combine the headers with the data to create an associative array
                $record = array_combine($headers, $data);

                // Insert the record into the raw_data table
                CrmTransaction::create([
                    'transactionId' => $record['transactionId'] ?? null,
                    'parentTxnId' => $record['parentTxnId'] ?? null,
                    'merchant' => $record['merchant'] ?? null,
                    'merchantDescriptor' => $record['merchantDescriptor'] ?? null,
                    'midNumber' => $record['midNumber'] ?? null,
                    'merchantId' => $record['merchantId'] ?? null,
                    'dateCreated' => $record['dateCreated'] ?? null,
                    'dateUpdated' => $record['dateUpdated'] ?? null,
                    'txnType' => $record['txnType'] ?? null,
                    'responseType' => $record['responseType'] ?? null,
                    'responseText' => $record['responseText'] ?? null,
                    '3DTxnResult' => $record['3DTxnResult'] ?? null,
                    'merchantTxnId' => $record['merchantTxnId'] ?? null,
                    'clientTxnId' => $record['clientTxnId'] ?? null,
                    'authCode' => $record['authCode'] ?? null,
                    'avsResponse' => $record['avsResponse'] ?? null,
                    'cvvResponse' => $record['cvvResponse'] ?? null,
                    'totalAmount' => $record['totalAmount'] ?? null,
                    'surcharge' => $record['surcharge'] ?? null,
                    'orderId' => $record['orderId'] ?? null,
                    'orderAgentName' => $record['orderAgentName'] ?? null,
                    'clientOrderId' => $record['clientOrderId'] ?? null,
                    'billingCycleNumber' => $record['billingCycleNumber'] ?? null,
                    'recycleNumber' => $record['recycleNumber'] ?? null,
                    'campaignId' => $record['campaignId'] ?? null,
                    'campaignName' => $record['campaignName'] ?? null,
                    'campaignCategoryName' => $record['campaignCategoryName'] ?? null,
                    'firstName' => $record['firstName'] ?? null,
                    'lastName' => $record['lastName'] ?? null,
                    'orderType' => $record['orderType'] ?? null,
                    'customerId' => $record['customerId'] ?? null,
                    'emailAddress' => $record['emailAddress'] ?? null,
                    'phoneNumber' => $record['phoneNumber'] ?? null,
                    'address1' => $record['address1'] ?? null,
                    'address2' => $record['address2'] ?? null,
                    'city' => $record['city'] ?? null,
                    'state' => $record['state'] ?? null,
                    'country' => $record['country'] ?? null,
                    'postalCode' => $record['postalCode'] ?? null,
                    'ipAddress' => $record['ipAddress'] ?? null,
                    'paySource' => $record['paySource'] ?? null,
                    'cardBin' => $record['cardBin'] ?? null,
                    'cardLast4' => $record['cardLast4'] ?? null,
                    'cardType' => $record['cardType'] ?? null,
                    'cardIsPrepaid' => $record['cardIsPrepaid'] ?? null,
                    'cardIsDebit' => $record['cardIsDebit'] ?? null,
                    'achBankName' => $record['achBankName'] ?? null,
                    'achRoutingNumber' => $record['achRoutingNumber'] ?? null,
                    'achAccountNumber' => $record['achAccountNumber'] ?? null,
                    'sourceTitle' => $record['sourceTitle'] ?? null,
                    'affId' => $record['affId'] ?? null,
                    'sourceValue1' => $record['sourceValue1'] ?? null,
                    'sourceValue2' => $record['sourceValue2'] ?? null,
                    'sourceValue3' => $record['sourceValue3'] ?? null,
                    'sourceValue4' => $record['sourceValue4'] ?? null,
                    'sourceValue5' => $record['sourceValue5'] ?? null,
                    'custom1' => $record['custom1'] ?? null,
                    'custom2' => $record['custom2'] ?? null,
                    'custom3' => $record['custom3'] ?? null,
                    'custom4' => $record['custom4'] ?? null,
                    'custom5' => $record['custom5'] ?? null,
                    'isChargedback' => $record['isChargedback'] ?? null,
                    'chargebackAmount' => $record['chargebackAmount'] ?? null,
                    'chargebackDate' => $record['chargebackDate'] ?? null,
                    'chargebackReasonCode' => $record['chargebackReasonCode'] ?? null,
                    'chargebackNote' => $record['chargebackNote'] ?? null,
                    'refundReason' => $record['refundReason'] ?? null,
                    'currencyCode' => $record['currencyCode'] ?? null,
                    'currencySymbol' => $record['currencySymbol'] ?? null,
                    'paySourceId' => $record['paySourceId'] ?? null,
                    'mcc' => $record['mcc'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Close the CSV file
            fclose($handle);
            // recived alerts with no errors
            $requests = EthocaRequest::factory(150)
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
                ])->for(
                        EthocaResponse::factory([
                            'major_code' => fake()->randomElement([2, 3, 4]),
                            'status' => 'Fail',
                            'number_of_alerts' => 0,
                        ])->for(
                                EthocaRequest::factory(
                                    [
                                        'title' => 'Ethoca 360 Alerts Request',
                                        'ethoca_fun_code' => '1',
                                    ]
                                )
                            )
                    )->create();
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

            //handle acked alerts using CRM actions
            $actions = ['Send Email', 'Call Customer', 'Block Account', 'Refund Paid Amount', "Refund Confirmed"];
            foreach ($ack_alerts as $alert) {
                foreach ($actions as $action) {
                    $status = fake()->boolean();
                    $max_error_count = 3;

                    while (!$status && $max_error_count) {
                        EthocaError::factory(['model' => CrmAction::class])
                            ->for(
                                CrmAction::factory([
                                    'name' => $action,
                                    'link' => fake()->url(),
                                    'status' => 'failed'
                                ])->for($alert)
                            )->create();
                        $max_error_count--;
                    }
                    CrmAction::factory([
                        'name' => $action,
                        'link' => fake()->url(),
                        'status' => 'completed',
                    ])->for($alert)->create();
                    if ($action == 'Refund Confirmed') {
                        $alert->is_handled = true;
                        $alert->save();
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
                } else {
                    EthocaError::factory([
                        'model' => EthocaResponse::class,
                    ])->for($update_res)->create();
                }
            }
        }
    }
}
