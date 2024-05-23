<?php

namespace App\Models;

use App\Models\Traits\HasError;
use Database\Factories\AlertsFactory;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\WebhookClient\Models\WebhookCall;

class EthocaAlert extends Model
{
    use HasFactory, HasError;
    protected $table = 'ethoca_alerts';

    protected $guarded = [];

    /**
     * Get the ethoca alert response that owns the ethoca alert.
     *
     */
    public function ethocaResponse(): BelongsTo
    {
        return $this->belongsTo(EthocaResponse::class);
    }

    public function webhookCall(): BelongsTo
    {
        return $this->belongsTo(WebhookCall::class);
    }

    public function webhookCalls(): HasMany
    {
        return $this->hasMany(WebhookCall::class, 'ethoca_id', 'ethoca_id');
    }
    public function webhooksErrors(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->webhookCalls()->where('is_success', false)->get();
            }
        );
    }
    public function crmActions(): HasMany
    {
        return $this->hasMany(CrmAction::class);
    }

    public function crmTransaction(): BelongsTo
    {
        return $this->belongsTo(CrmTransaction::class);
    }

    public function ethocaAcknowledgements(): HasMany
    {
        return $this->hasMany(EthocaAcknowledgement::class);
    }

    public function ethocaUpdates(): HasMany
    {
        return $this->hasMany(EthocaUpdate::class);
    }
    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }
    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return AlertsFactory::new();
    }
    public static function mapAlertResponseToRecord($alert): EthocaAlert
    {
        $eth_alert = new EthocaAlert();
        $eth_alert->ethoca_id = $alert->EthocaID;
        $eth_alert->type = $alert->Type;
        $eth_alert->alert_timestamp = $alert->AlertTimestamp;
        $eth_alert->issuer = $alert->Issuer;
        $eth_alert->card_number = $alert->CardNumber;
        $eth_alert->card_bin = $alert->CardBIN;
        $eth_alert->card_last4 = $alert->CardLast4;
        $eth_alert->arn = $alert->ARN;
        $eth_alert->transaction_timestamp = $alert->TransactionTimestamp;
        $eth_alert->merchant_descriptor = $alert->MerchantDescriptor;
        $eth_alert->member_id = $alert->MemberID;
        $eth_alert->mcc = $alert->MCC;
        $eth_alert->amount = $alert->Amount;
        $eth_alert->currency = $alert->Currency;
        $eth_alert->transaction_type = $alert->TransactionType;
        $eth_alert->initiated_by = $alert->InitiatedBy;
        $eth_alert->is_3d_secure = $alert->Is3DSecure;
        $eth_alert->source = $alert->Source;
        $eth_alert->auth_code = $alert->AuthCode;
        $eth_alert->merchant_member_name = $alert->MerchantMemberName;
        $eth_alert->ethoca_transaction_id = $alert->TransactionId;
        $eth_alert->chargeback_reason_code = $alert->ChargebackReasonCode;
        $eth_alert->chargeback_amount = $alert->ChargebackAmount;
        $eth_alert->chargeback_currency = $alert->ChargebackCurrency;
        return $eth_alert;
    }

    function mappAlertRecordToRequest()
    {
        return [
            'EthocaID' => $this->ethoca_id,
            'Type' => $this->type,
            'AlertTimestamp' => $this->alert_timestamp,
            'Issuer' => $this->issuer,
            'CardNumber' => $this->card_number,
            'CardBIN' => $this->card_bin,
            'CardLast4' => $this->card_last4,
            'ARN' => $this->arn,
            'TransactionTimestamp' => $this->transaction_timestamp,
            'MerchantDescriptor' => $this->merchant_descriptor,
            'MemberID' => $this->member_id,
            'MCC' => $this->mcc,
            'Amount' => $this->amount,
            'Currency' => $this->currency,
            'TransactionType' => $this->transaction_type,
            'InitiatedBy' => $this->initiated_by,
            'Is3DSecure' => $this->is_3d_secure,
            'Source' => $this->source,
            'AuthCode' => $this->auth_code,
            'MerchantMemberName' => $this->merchant_member_name,
            'TransactionId' => $this->ethoca_transaction_id,
            'ChargebackReasonCode' => $this->chargeback_reason_code,
            'ChargebackAmount' => $this->chargeback_amount,
            'ChargebackCurrency' => $this->chargeback_currency,
        ];
    }
    #region Accessors
    public function errors(): Attribute
    {
        $errors = collect([]);

        // $errors = $errors->concat($this->ethocaResponse->errors);
        $errors = $errors->concat($this->ethocaAcknowledgements()->get()->map(function ($ack) {
            return $ack->errors;
        })->collapse());
        $errors = $errors->concat($this->ethocaUpdates()->get()->map(function ($ack) {
            return $ack->errors;
        })->collapse());
        $errors = $errors->concat($this->crmActions()->get()->map(function ($ack) {
            return $ack->errors;
        })->collapse());
        $errors = $errors->concat(
            EthocaError::where(
                [
                    'model' => self::class,
                    'model_id' => $this->id
                ]
            )->get()
        );
        // dd($errors);
        return Attribute::make(
            get: function () use ($errors) {
                return $errors;
            }
        );
    }
    #endregion
}
