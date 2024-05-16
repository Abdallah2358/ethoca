<?php

namespace App\Console\Commands;

use App\Models\EthocaAcknowledgement;
use App\Models\EthocaAlert;
use App\Models\EthocaAlertError;
use App\Models\EthocaAlertResponse;
use App\Models\EthocaError;
use App\Models\EthocaRequest;
use App\Models\EthocaResponse;
use Illuminate\Console\Command;

use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Facades\Http;

class MakeSoapAlertsRequest extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-soap-alerts-request {count=1} {--start=} {--end=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A command used to make a soap request to Ethoca 360 Alerts API to get alerts and store them in the database.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // start connection
        $phpFilePath = public_path("EthocaAlerts-Sandbox.wsdl");
        $client = new \SoapClient($phpFilePath);
        $count = $this->ask('How many times do you want to retry the request?', 1);
        $time_interval = $this->confirm('Do you wish to have time interval? ');
        $ethoca_args = array(["Username" => env('ETHOCA_USERNAME'), "Password" => env('ETHOCA_PASSWORD')]);
        if ($time_interval) {
            $start = $this->ask('what is the start date?', (new \DateTime('now'))->format('Y-m-d'));
            $end = $this->ask('what is the end date?', (new \DateTime('now'))->format('Y-m-d'));
            $ethoca_args = array_merge($ethoca_args, ["SearchStartDate" => $start, "SearchEndDate" => $end]);
        }

        // retry the request through the call 3 times
        while ($count) {
            // call Ethoca360Alerts
            $alert_response = $this->generateRequest($client, 'Ethoca360Alerts', $ethoca_args);
            // store response in db
            $count = ($alert_response->majorCode == 0 && $alert_response->Status == 'Success') ? 0 : $count; // if the call is successful stop the loop
            $alert_res_model = EthocaResponse::create([
                'major_code' => $alert_response->majorCode,
                'status' => $alert_response->Status,
                'number_of_alerts' => $alert_response->numberOfAlerts ?? 0,
                'ethoca_request_id' => $alert_response->ethoca_request_id ?? null,
            ]);

            // if it is saved successfully
            if ($alert_res_model->id) {
                // if the call is successful store all alerts in database
                if ($alert_response->majorCode == 0) {
                    foreach ($alert_response->Alerts as $alert) {
                        $ethoca_alert = EthocaAlert::mapAlertResponseToRecord($alert);
                        $ethoca_alert->save();
                    }

                    // Now Start Acklogment of Received Alerts
                    // chunk the alerts to 24 sized arrays to follow ethoca docs
                    foreach (array_chunk($alert_response->Alerts, 24, true) as $alertsArray) {
                        foreach ($alertsArray as $alerts) {
                            $args = array(array_merge($ethoca_args, ["Alerts" => $alerts]));

                            $ack_response = $this->generateRequest($client, 'EthocaAlertAcknowledgement', $args);
                            $arc_response_record = EthocaResponse::create([
                                'major_code' => $ack_response->majorCode,
                                'status' => $ack_response->Status,
                                'number_of_alerts' => $ack_response->numberOfAlerts ?? 0,
                                'ethoca_request_id' => $ack_response->ethoca_request_id ?? null,
                            ]);
                            foreach ($ack_response->AlertUpdateResponses as $ack_response) {
                                $ack = EthocaAcknowledgement::create([
                                    'ethoca_id' => $ack_response->EthocaID,
                                    'status' => $ack_response->Status,
                                    'ethoca_response_id' => $ack_response->id,
                                ]);
                                if (isset($ack_response->Errors) && isset($ack_response->Errors->Error)) {
                                    foreach ($ack_response->Errors->Error as $error) {
                                        EthocaError::create([
                                            'model' => EthocaAcknowledgement::class,
                                            'model_id' => $ethoca_alert->id,
                                            'code' => $error->code,
                                            'description' => $error->_,
                                        ]);
                                    }
                                }
                            }
                            // TODO : Save Ack request and response if needed
                        }
                    }
                }
            }
            $count--;
        }
    }
    public function generateRequest(\SoapClient $client, $ethoc_function, $ethoca_args, $start = null, $end = null): mixed
    {
        $title = 'Ethoca 360 Alerts Request';
        $alert_type = null;
        switch ($ethoc_function) {
            case 'Ethoca360Alerts':
                $title = 'Ethoca 360 Alerts Request';
                $alert_type = $ethoca_args['AlertType'] ?? 'all'; // default to 'all
                break;
            case 'EthocaAlertAcknowledgement':
                $title = 'Acknowledge Ethoca Alerts Request';
                break;
            case 'Ethoca360AlertsUpdate':
                $title = 'Update Ethoca Alerts Request';
                break;
        }
        $ethoca_request = EthocaRequest::create([
            'title' => $title,
            'alert_type' => $alert_type, // default to 'all
            'search_start_date' => $ethoca_args['SearchStartDate'] ?? null,
            'search_end_date' => $ethoca_args['SearchEndDate'] ?? null,
        ]);
        $response = $client->__soapCall($ethoc_function, $ethoca_args, ["trace" => true, "_connection_timeout" => 180]);
        // save all errors to database
        if (isset($response->Errors) && is_array($response->Errors->Error)) {
            foreach ($response->Errors->Error as $error) {
                EthocaError::create([
                    'model' => EthocaRequest::class,
                    'model_id' => $ethoca_request->id,
                    'code' => $error->code,
                    'description' => $error->_,
                ]);
            }
        } else {
            EthocaError::create([
                'model' => EthocaRequest::class,
                'model_id' => $ethoca_request->id,
                'code' => $response->Errors->Error->code,
                'description' => $response->Errors->Error->_,
            ]);
        }
        $response->ethoca_request_id = $ethoca_request->id;
        return $response;
    }
}
