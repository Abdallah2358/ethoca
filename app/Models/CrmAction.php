<?php

namespace App\Models;

use App\Models\Traits\HasError;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmAction extends Model
{
    use HasFactory, HasError;

    protected $table = 'crm_actions';

    protected $guarded = [];

    public function ethocaAlert(): BelongsTo
    {
        return $this->belongsTo(EthocaAlert::class);
    }


}
