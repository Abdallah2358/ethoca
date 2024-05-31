<?php

namespace App\Models\Traits;

use App\Models\Error;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasError
{
    public function errors(): Attribute
    {
        return Attribute::make(
            get: function () {
                return Error::where(
                    [
                        'model' => self::class,
                        'model_id' => $this->id
                    ]
                )->get();
            }
        );
    }
}
