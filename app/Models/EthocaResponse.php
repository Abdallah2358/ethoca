<?php

namespace App\Models;

use Database\Factories\ResponseFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EthocaResponse extends Model
{
    use HasFactory;
    protected $table = 'ethoca_responses';
    protected $guarded = [];
    public function ethocaRequest(): BelongsTo
    {
        return $this->belongsTo(EthocaRequest::class);
    }

    public function ethocaAlerts(): HasMany
    {
        return $this->hasMany(EthocaAlert::class);
    }
    public function ethocaAcknowledgements(): HasMany
    {
        return $this->hasMany(EthocaAcknowledgement::class);
    }
    public function ethocaUpdates(): HasMany
    {
        return $this->hasMany(EthocaUpdate::class);
    }

    protected static function newFactory(): Factory
    {
        return ResponseFactory::new();
    }
}
