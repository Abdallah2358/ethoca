<?php

namespace App\Models;

use App\Models\Traits\HasError;
use Database\Factories\UpdateFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EthocaUpdate extends Model
{
    use HasFactory,HasError;
    protected $table = 'ethoca_updates';
    protected $guarded = [];
    /**
     * Get the response that owns the EthocaUpdate
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ethocaResponse(): BelongsTo
    {
        return $this->belongsTo(EthocaResponse::class);
    }
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
