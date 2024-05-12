<?php

namespace App\Console\Commands;

use App\Models\EthocaAlert;
use App\Models\EthocaAlertError;
use App\Models\EthocaAlertResponse;
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
        $phpFilePath = public_path("EthocaAlerts-Sandbox.wsdl");
        $client = new \SoapClient($phpFilePath);
        $creds = array(["Username" => "Steveasdf", "Password" => "NetworkAdministrator"]);
        $alert_response = $client->__soapCall("Ethoca360Alerts", $creds, ["trace" => true, "_connection_timeout" => 180]);
        $this->info(json_encode($alert_response));
        $alert_res_model = EthocaAlertResponse::create([
            'major_code' => $alert_response->majorCode,
            'status' => $alert_response->Status,
            'number_of_alerts' => $alert_response->numberOfAlerts ?? 0,
        ]);
        if ($alert_res_model->id) {
            foreach ($alert_response->Errors as $error) {
                EthocaAlertError::create([
                    'ethoca_alert_response_id' => $alert_res_model->id,
                    'code' => $error->code,
                    'description' => $error->_,
                ]);
            }
            if ($alert_response->majorCode == 0) {
                foreach ($alert_response->Alerts as $alert) {
                    EthocaAlert::create($alert);
                }
                foreach (array_chunk($alert_response->Alerts,24,true) as $alertsArrays) {
                    foreach ($alertsArrays as $alerts) {
                        $args = array(array_merge($creds, ["Alerts" => $alerts]));
                        $ack_response = $client->__soapCall("EthocaAlertAcknowledgement ", $args, ["trace" => true, "_connection_timeout" => 180]);
                        $this->info(json_encode($ack_response));
                    }
                }
            }
        }

    }
}
