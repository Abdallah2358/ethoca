<?php

namespace App\Models;

use App\Models\Traits\HasError;
use Database\Factories\AcknowledgementFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EthocaAcknowledgement extends Model
{
    use HasFactory,HasError;

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


    protected static function newFactory()
    {
        return AcknowledgementFactory::new();
    }
}
