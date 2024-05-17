<?php

namespace App\Models;

use Database\Factories\UpdateFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EthocaUpdate extends Model
{
    use HasFactory;
    protected $table = 'ethoca_alert_updates';
    protected $guarded = [];
    public function getUpdateForRequest()
    {
        $alert_update = array();
        $optionalEthocaName = ['Refunded', 'FirstPartyFraud', 'AmountStopped', 'ActionTimestamp'];
        $optional = ['refunded', 'first_party_fraud', 'amount_stopped', 'action_timestamp'];
        $alert_update['EthocaID'] = $this->ethoca_id;
        $alert_update['Outcome'] = $this->outcome;
        for ($i = 0; $i < count($optional); $i++) {
            if ($this->$optional[$i] != null) {
                $alert_update[$optionalEthocaName[$i]] = $this->{$optional[$i]};
            }
        }
        return $alert_update;
    }

    protected static function newFactory()
    {
        return UpdateFactory::new();
    }
}
