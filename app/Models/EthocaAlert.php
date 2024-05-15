<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EthocaAlert extends Model
{
    use HasFactory;
    protected $table = 'ethoca_alerts';

    protected $guarded = [];
    /**
     * Get the ethoca alert response that owns the ethoca alert.
     *
     */
    public function ethocaAlertResponse(): BelongsTo
    {
        return $this->belongsTo(EthocaAlertResponse::class);
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
}
