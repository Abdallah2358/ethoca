<?php

namespace App\Models\Traits;

use App\Models\EthocaError;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasError
{
    public function errors(): Attribute
    {
        return Attribute::make(
            get: function () {
                return EthocaError::where(
                    [
                        'model' => self::class,
                        'model_id' => $this->id
                    ]
                )->get()->toArray();
            }
        );
    }
}
