<?php

namespace App\Jobs;

use App\Models\EthocaAlert;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob as SpatieProcessWebhookJob;
use Spatie\WebhookClient\Models\WebhookCall;

class ProcessWebhookJob extends SpatieProcessWebhookJob
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
        $payload = $this->webhookCall->payload;
        $payload['alert_timestamp'] = date_format(date_create($payload['alert_timestamp']), 'Y-m-d\TH:i:s.v');
        $payload['transaction_timestamp'] = date_format(date_create($payload['transaction_timestamp']), 'Y-m-d\TH:i:s.v');
        $alert = EthocaAlert::create($payload);
        $alert->webhookCall()->associate($this->webhookCall);
        $alert->save();
        // throw new \Exception("Error Processing Request", 1);
    }
}
