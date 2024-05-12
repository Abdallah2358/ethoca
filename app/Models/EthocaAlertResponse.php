<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EthocaAlertResponse extends Model
{
    use HasFactory;
    protected $table = 'ethoca_alert_responses';
    protected $fillable = [
        'major_code',
        'status',
        'number_of_alerts'
    ];
    /**
     * Get the ethoca alerts for the ethoca alert response.
     *
     */
    public function ethocaAlerts() : HasMany
    {
        return $this->hasMany(EthocaAlert::class);
    }

    /**
     * Get the ethoca alert errors for the ethoca alert response.
     *
     */
    public function ethocaAlertErrors() : HasMany
    {
        return $this->hasMany(EthocaAlertError::class);
    }
}
