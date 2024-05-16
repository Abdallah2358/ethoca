<?php

namespace App\Http\Controllers;

// use App\Models\Enums\EthocaFunctions;
use App\Models\EthocaError;
use App\Models\EthocaRequest;
use App\Models\EthocaUpdate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class EthocaController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function updateAlert(Request $request)
    {
        $phpFilePath = public_path("EthocaAlerts-Sandbox.wsdl");
        // $this->info('pub path : ' . $phpFilePath);
        $client = new \SoapClient($phpFilePath);
        $updates = EthocaUpdate::where('is_sent', 0)->get()->map(function ($update) {
            return $update->getUpdateForRequest();
        })->toArray();
        $response = EthocaRequest::generateRequest($client, 'Ethoca360AlertsUpdate', ["Username" => env('ETHOCA_USERNAME'), "Password" => env('ETHOCA_PASSWORD'), 'AlertUpdates' => $updates]);
        // $response = $client->__soapCall(
        //     'Ethoca360AlertsUpdate',
        //     array(["Username" => env('ETHOCA_USERNAME'), "Password" => env('ETHOCA_PASSWORD'),'AlertUpdates'=>[]]),
        //     // ["trace" => true, "_connection_timeout" => 180]
        // );
        // return response()->json(['msg' => 'Hello', 'request-body' => $response]);
        foreach ($response->AlertUpdateResponses as $update_response) {
            $update = EthocaUpdate::where('ethoca_id', $update_response->EthocaID)->first();
            if ($update_response->Status == 'success') {
                $update->is_sent = 1;
                $update->status = 1;
                $update->save();
            } else {
                $errors = EthocaError::generateErrorsFromResponse($update_response, $update);
            }
        }
    }
}
