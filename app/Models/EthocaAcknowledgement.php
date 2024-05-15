<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EthocaAcknowledgement extends Model
{
    use HasFactory;

    protected $table = 'ethoca_acknowledgements';

    protected $guarded = [];

    public function ethocaAlert(): BelongsTo
    {
        return $this->belongsTo(EthocaAlert::class);
    }

    public function ethocaRequest(): BelongsTo
    {
        return $this->belongsTo(EthocaRequest::class);
    }

    public function ethocaResponse(): BelongsTo
    {
        return $this->belongsTo(EthocaResponse::class);
    }

}
