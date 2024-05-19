<?php

namespace App\Models;

use App\Models\Traits\HasError;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CrmTransaction extends Model
{
    use HasFactory, HasError;
    public function ethocaAlerts(): HasMany
    {
        return $this->hasMany(EthocaAlert::class);

    }
}
