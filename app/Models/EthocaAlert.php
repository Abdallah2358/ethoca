<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EthocaAlert extends Model
{
    use HasFactory;
    protected $table = 'ethoca_alerts';

    /**
     * Get the ethoca alert response that owns the ethoca alert.
     *
     */
    public function ethocaAlertResponse(): BelongsTo
    {
        return $this->belongsTo(EthocaAlertResponse::class);
    }
}
