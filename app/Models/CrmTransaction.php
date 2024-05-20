<?php

namespace App\Models;

use App\Models\Traits\HasError;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CrmTransaction extends Model
{
    use HasFactory, HasError;
    public function ethocaAlert(): HasOne
    {
        return $this->hasOne(EthocaAlert::class);
    }
}
