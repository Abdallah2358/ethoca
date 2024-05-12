<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EthocaAcknowledgementError extends Model
{
    use HasFactory;
    protected $table = 'ethoca_acknowledgement_errors';
    protected $guarded = [];

    /**
     * Get the ethoca acknowledgement response that owns the ethoca acknowledgement error.
     *
     */
    public function ethocaAcknowledgementResponse() : BelongsTo
    {
        return $this->belongsTo(EthocaAcknowledgementResponse::class);
    }
}
