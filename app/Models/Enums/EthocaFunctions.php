<?
namespace App\Models\Enums;

class EthocaFunctions
{
    static public $GET_ALERTS = 'Ethoca360Alerts';
    static public $ACK_ALERTS = 'EthocaAlertAcknowledgement';
    static public $UPDATE_ALERTS = 'Ethoca360AlertsUpdate';
    static public $CREDENTIALS = array(["Username" => env('ETHOCA_USERNAME'), "Password" => env('ETHOCA_PASSWORD')]);
}
