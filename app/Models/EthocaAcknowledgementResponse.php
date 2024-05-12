<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EthocaAcknowledgementResponse extends Model
{
    use HasFactory;
    protected $table = 'ethoca_acknowledgement_responses';
    protected $guarded = [];

    /**
     * Get the ethoca acknowledgements updates for the ethoca acknowledgement response.
     *
     */
    public function updates() : HasMany
    {
        return $this->hasMany(EthocaAcknowledgementUpdate::class);
    }

    /**
     * Get the ethoca acknowledgements errors for the ethoca acknowledgement response.
     *
     */
    public function errors() : HasMany
    {
        return $this->hasMany(EthocaAcknowledgementError::class);
    }
}
