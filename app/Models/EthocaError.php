<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EthocaError extends Model
{
    use HasFactory;
    public function error_origin(): BelongsTo
    {
        return $this->belongsTo($this->model, $this->model_id);
    }
}
