<?php

namespace App\Http\Controllers;

// use App\Models\Enums\EthocaFunctions;
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

        $response = $client->__soapCall(
            'Ethoca360AlertsUpdate',
            array(["Username" => env('ETHOCA_USERNAME'), "Password" => env('ETHOCA_PASSWORD'),'AlertUpdates'=>[]]),
            // ["trace" => true, "_connection_timeout" => 180]
        );
        return response()->json(['msg' => 'Hello', 'request-body' => $response]);
        foreach ($request->alerts as $key => $value) {
            # code...
        }
        info($request->alerts);
        $alerts = array($request->alerts);
        // array(
        //     [
        //         array(
        //             [
        //                 'EthocaID' => '',

        //                 'Outcome' => '',
        //                 // <!--Optional:-->
        //                 'Refunded' => '',
        //                 // <!--Optional:-->
        //                 'FirstPartyFraud' => '',
        //                 // <!--Optional:-->
        //                 'AmountStopped' => '',
        //                 // <!--Optional:-->
        //                 'ActionTimestamp' => '',
        //                 // <!--Optional:-->
        //                 'Comments' => ''
        //             ]
        //         )
        //     ]
        // );
    }
}
