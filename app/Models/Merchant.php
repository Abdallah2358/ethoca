<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Merchant extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $appends = [
        'alerts_data'
    ];
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function alerts(): HasMany
    {
        return $this->hasMany(EthocaAlert::class);
    }
    public function getAlertsCountAttribute(): int
    {
        return $this->alerts()->count();
    }
    public function getCompanyNameAttribute(): string
    {
        return $this->company->name;
    }
    public function alertsData(): Attribute
    {
        return Attribute::make(
            get: function () {
                return EthocaAlert::merchant_data($this->id);
            }
        );
    }
    // public function handledAlerts(): Attribute
    // {
    //     return Attribute::make(
    //         get: function () {

    //             return $this->alerts()->where('is_handled', true)->where('is_paid', true);
    //         }
    //     );
    // }
    // public function unhandledAlerts(): Attribute
    // {
    //     return Attribute::make(
    //         get: function () {

    //             return $this->alerts()->where('is_handled', false);
    //         }
    //     );
    // }
    // public function getHandledAlertsCountAttribute(): int
    // {
    //     return $this->handledAlerts->count();
    // }
    // public function getUnhandledAlertsCountAttribute(): int
    // {
    //     return $this->alerts()->where('is_handled', false)->count();
    // }
    // public function getHandledAlertsTotalAmountAttribute(): float
    // {
    //     return $this->handledAlerts->sum('amount');
    // }
}
