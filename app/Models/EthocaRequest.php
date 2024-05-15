<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EthocaRequest extends Model
{
    use HasFactory;
    protected $table = 'ethoca_requests';
    protected $guarded = [];

    public function ethocaResponse() : HasOne
    {
        return $this->hasOne(EthocaResponse::class);
    }

    public function ethocaAlerts() : HasMany
    {
        return $this->hasMany(EthocaAlert::class);
    }

    public function ethocaAcknowledgements() : HasMany
    {
        return $this->hasMany(EthocaAcknowledgement::class);
    }

    public function ethocaUpdates() : HasMany
    {
        return $this->hasMany(EthocaUpdate::class);
    }

}
