<?php

namespace App\Jobs;

use App\Models\EthocaAlert;
use App\Models\EthocaError;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob as SpatieProcessWebhookJob;


class ANProcessWebhookJob extends SpatieProcessWebhookJob
{

    /**
     * Create a new job instance.
     */
    // public function __construct()
    // {
    //     //
    // }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $ethoca_id = $this->webhookCall->payload['ethoca_id'];
        try {
            $payload = $this->webhookCall->payload;
            $payload['alert_timestamp'] = date_format(date_create($payload['alert_timestamp']), 'Y-m-d\TH:i:s.v');
            $payload['transaction_timestamp'] = date_format(date_create($payload['transaction_timestamp']), 'Y-m-d\TH:i:s.v');
            $this->webhookCall->ethoca_id = $ethoca_id;
            $alert = EthocaAlert::where('ethoca_id', $ethoca_id);
            if ($alert->exists()) {
                $alert = $alert->first();
                EthocaError::create([
                    'ethoca_id' => $ethoca_id,
                    'code' => 'DUP_ALERT',
                    'description' => 'Duplicate Alert',
                    'Notes' => 'Ethoca Alert #' . $ethoca_id . ' already exists in the system.',
                ]);
            } else {
                $alert = new EthocaAlert($payload);
                $alert->webhookCall()->associate($this->webhookCall);
                $alert->save();
            }
            $this->appendToChain(new ProcessAlert($alert));
            // ProcessAlert::dispatchAfterResponse($alert);
            $this->webhookCall->is_success = true;
            $this->webhookCall->save();
        } catch (\Throwable $th) {
            $this->webhookCall->ethoca_id = $ethoca_id;
            $this->webhookCall->save();
            throw $th;
        }

        // throw new \Exception("Error Processing Request", 1);
    }
}
