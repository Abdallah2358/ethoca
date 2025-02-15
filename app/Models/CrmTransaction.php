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
    protected $guarded = ['id'];
    protected $fillable = [
        'ethoca_id',
        'transactionId',
        'parentTxnId',
        'merchant',
        'merchantDescriptor',
        'midNumber',
        'merchantId',
        'dateCreated',
        'dateUpdated',
        'txnType',
        'responseType',
        'responseText',
        '3DTxnResult',
        'merchantTxnId',
        'clientTxnId',
        'authCode',
        'avsResponse',
        'cvvResponse',
        'totalAmount',
        'surcharge',
        'orderId',
        'orderAgentName',
        'clientOrderId',
        'billingCycleNumber',
        'recycleNumber',
        'campaignId',
        'campaignName',
        'campaignCategoryName',
        'firstName',
        'lastName',
        'orderType',
        'customerId',
        'emailAddress',
        'phoneNumber',
        'address1',
        'address2',
        'city',
        'state',
        'country',
        'postalCode',
        'ipAddress',
        'paySource',
        'cardBin',
        'cardLast4',
        'cardType',
        'cardIsPrepaid',
        'cardIsDebit',
        'achBankName',
        'achRoutingNumber',
        'achAccountNumber',
        'sourceTitle',
        'affId',
        'sourceValue1',
        'sourceValue2',
        'sourceValue3',
        'sourceValue4',
        'sourceValue5',
        'custom1',
        'custom2',
        'custom3',
        'custom4',
        'custom5',
        'isChargedback',
        'chargebackAmount',
        'chargebackDate',
        'chargebackReasonCode',
        'chargebackNote',
        'refundReason',
        'currencyCode',
        'currencySymbol',
        'paySourceId',
        'mcc',
    ];
    public function ethocaAlert(): HasOne
    {
        return $this->hasOne(EthocaAlert::class);
    }
}
