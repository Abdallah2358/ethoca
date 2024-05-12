<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EthocaAlertError extends Model
{
    use HasFactory;
    protected $table = 'ethoca_alert_errors';
    protected $guarded = [];

    /**
     * Get the ethoca alert response that owns the ethoca alert error.
     *
     */
    public function ethocaAlertResponse() : BelongsTo
    {
        return $this->belongsTo(EthocaAlertResponse::class);
    }

}
