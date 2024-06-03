<?php

namespace App\Jobs;

use App\Models\EthocaAlert;
use App\Models\EthocaRequest;
use App\Models\EthocaUpdate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use SoapClient;

class ProcessUpdateEthoca implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected EthocaAlert $alert,
        protected string $outcome = 'stopped',
        protected string $refunded = 'refunded',
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $phpFilePath = public_path("EthocaAlerts-Sandbox.wsdl");
        $client = new SoapClient($phpFilePath);
        $update = EthocaUpdate::create(
            [
                'ethoca_alert_id' => $this->alert->id,
                'ethoca_id' => $this->alert->ethoca_id,
                'outcome' => $this->outcome,
                'refunded' => $this->refunded,
            ]
        );
        $updates = [
            [
                'EthocaID' => $this->alert->ethoca_id,
                'Outcome' => $this->outcome,
                'Refunded' => $this->refunded,
            ],
        ];
        $response = EthocaRequest::generateRequest(
            $client,
            'Ethoca360AlertsUpdate',
            [
                "Username" => env('ETHOCA_USERNAME'),
                "Password" => env('ETHOCA_PASSWORD'),
                'AlertUpdates' => $updates,
            ]
        );

        $status = null;
        if (isset($response->AlertUpdateResponses)) {
            if (isset($response->AlertUpdateResponses->AlertUpdateResponse)) {
                $status = $response->AlertUpdateResponses->AlertUpdateResponse->status;
            }
        }

        if ($status == 'success') {
            $this->alert->update(['is_updated' => 1]);
            $this->alert->save();
        }
    }
}
