<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Company extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $appends = ['alerts', 'merchants_count'];
    public function merchants()
    {
        return $this->hasMany(Merchant::class);
    }

    public function alerts(): Attribute
    {
        return Attribute::make(
            get: function () {
                $merchants = $this->merchants()->whereHas('alerts')->get();
                $alerts = $merchants->flatMap(
                    function ($merchant) {
                        return $merchant->alerts;
                    }
                );
                return $alerts;
            }
        );
    }
    public function merchantsCount(): Attribute
    {
        return Attribute::make(
            get: function () {

                return $this->merchants()->count();
            }
        );
    }
}
