<?php

namespace App\Console\Commands;

use App\Models\EthocaAlert;
use App\Models\EthocaAlertError;
use App\Models\EthocaAlertResponse;
use App\Models\EthocaError;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\Http;

class MakeSoapAlertsRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-soap-alerts-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // start connection
        $phpFilePath = public_path("EthocaAlerts-Sandbox.wsdl");
        $this->info('pub path : ' . $phpFilePath);
        $client = new \SoapClient($phpFilePath);
        $creds = array(["Username" => env('ETHOCA_USERNAME'), "Password" => env('ETHOCA_PASSWORD')]);

        // call Ethoca360Alerts
        $alert_response = $client->__soapCall("Ethoca360Alerts", $creds, ["trace" => true, "_connection_timeout" => 180]);
        // log response to console
        // $this->info(json_encode($alert_response));

        // store response in db
        $alert_res_model = EthocaAlertResponse::create([
            'major_code' => $alert_response->majorCode,
            'status' => $alert_response->Status,
            'number_of_alerts' => $alert_response->numberOfAlerts ?? 0,
        ]);

        // if it is saved successfully
        if ($alert_res_model->id) {
            // save all errors to database
            if (isset($alert_response->Errors) && isset($alert_response->Errors->Error)) {
                // $this->info(json_encode($alert_response->Errors->Error));
                # code...
                foreach ($alert_response->Errors->Error as $error) {
                    EthocaError::create([
                        'model' => EthocaAlertResponse::class,
                        'model_id' => $alert_res_model->id,
                        'code' => $error->code,
                        'description' => $error->_,
                    ]);
                }
            }

            // if the call is successful store all alerts in database
            if ($alert_response->majorCode == 0) {
                foreach ($alert_response->Alerts as $alert) {
                    $ethoca_alert= EthocaAlert::mapAlertResponseToRecord($alert);
                    $ethoca_alert->save();
                    // TODO : Check this in testing to make sure they match
                }

                // Now Start Acklogment of Received Alerts
                // chunk the alerts to 24 sized arrays to follow ethoca docs
                foreach (array_chunk($alert_response->Alerts, 24, true) as $alertsArrays) {
                    foreach ($alertsArrays as $alerts) {
                        $args = array(array_merge($creds, ["Alerts" => $alerts]));
                        $ack_response = $client->__soapCall("EthocaAlertAcknowledgement ", $args, ["trace" => true, "_connection_timeout" => 180]);
                        // Log the ack response to console
                        $this->info(json_encode($ack_response));
                        // TODO : Save Ack request and response if needed
                    }
                }
            }
        }

    }
}
