<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EthocaAcknowledgementUpdate extends Model
{
    use HasFactory;
    protected $table = 'ethoca_acknowledgement_updates';
    protected $guarded = [];

    /**
     * Get the ethoca acknowledgement response that owns the ethoca acknowledgement update.
     *
     */
    public function ethocaAcknowledgementResponse(): BelongsTo
    {
        return $this->belongsTo(EthocaAcknowledgementResponse::class);
    }
}
