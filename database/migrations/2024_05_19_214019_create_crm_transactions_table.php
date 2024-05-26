<?php

use App\Models\EthocaAlert;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('crm_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(EthocaAlert::class)->nullable()->default(null)->comment('The alert id');
            $table->string('ethoca_id')->nullable()->default(null)->index()->comment('The Ethoca ID');
            $table->mediumInteger('transactionId')->nullable()->default(null);
            $table->string('parentTxnId', 255)->nullable()->default(null);
            $table->string('merchant', 255)->nullable()->default(null);
            $table->string('merchantDescriptor', 255)->nullable()->default(null);
            $table->string('midNumber', 255)->nullable()->default(null);
            $table->string('merchantId', 100)->nullable()->default(null);
            $table->dateTime('dateCreated')->nullable()->default(null);
            $table->dateTime('dateUpdated')->nullable()->default(null);
            $table->string('txnType', 100)->nullable()->default(null);
            $table->string('responseType', 100)->nullable()->default(null);
            $table->string('responseText', 100)->nullable()->default(null);
            $table->string('3DTxnResult', 100)->nullable()->default(null);
            $table->string('merchantTxnId', 100)->nullable()->default(null);
            $table->string('clientTxnId', 100)->nullable()->default(null);
            $table->string('authCode', 100)->nullable()->default(null);
            $table->string('avsResponse', 100)->nullable()->default(null);
            $table->string('cvvResponse', 100)->nullable()->default(null);
            $table->decimal('totalAmount', 16, 2)->nullable()->default(null);
            $table->decimal('surcharge', 16, 2)->nullable()->default(null);
            $table->string('orderId', 255)->nullable()->default(null);
            $table->string('orderAgentName', 255)->nullable()->default(null);
            $table->string('clientOrderId', 255)->nullable()->default(null);
            $table->mediumInteger('billingCycleNumber')->nullable()->default(null);
            $table->string('recycleNumber', 255)->nullable()->default(null);
            $table->string('campaignId', 255)->nullable()->default(null);
            $table->string('campaignName', 255)->nullable()->default(null);
            $table->string('campaignCategoryName', 255)->nullable()->default(null);
            $table->string('firstName', 255)->nullable()->default(null);
            $table->string('lastName', 255)->nullable()->default(null);
            $table->string('orderType', 255)->nullable()->default(null);
            $table->mediumInteger('customerId')->nullable()->default(null);
            $table->string('emailAddress', 255)->nullable()->default(null);
            $table->string('phoneNumber', 255)->nullable()->default(null);
            $table->string('address1', 255)->nullable()->default(null);
            $table->string('address2', 255)->nullable()->default(null);
            $table->string('city', 255)->nullable()->default(null);
            $table->string('state', 255)->nullable()->default(null);
            $table->string('country', 255)->nullable()->default(null);
            $table->string('postalCode', 255)->nullable()->default(null);
            $table->string('ipAddress', 255)->nullable()->default(null);
            $table->string('paySource', 255)->nullable()->default(null);
            $table->string('cardBin', 255)->nullable()->default(null);
            $table->string('cardLast4', 255)->nullable()->default(null);
            $table->string('cardType', 255)->nullable()->default(null);
            $table->mediumInteger('cardIsPrepaid')->nullable()->default(null);
            $table->mediumInteger('cardIsDebit')->nullable()->default(null);
            $table->string('achBankName', 255)->nullable()->default(null);
            $table->string('achRoutingNumber', 255)->nullable()->default(null);
            $table->string('achAccountNumber', 255)->nullable()->default(null);
            $table->string('sourceTitle', 255)->nullable()->default(null);
            $table->string('affId', 255)->nullable()->default(null);
            $table->string('sourceValue1', 255)->nullable()->default(null);
            $table->string('sourceValue2', 255)->nullable()->default(null);
            $table->string('sourceValue3', 255)->nullable()->default(null);
            $table->string('sourceValue4', 255)->nullable()->default(null);
            $table->string('sourceValue5', 255)->nullable()->default(null);
            $table->string('custom1', 255)->nullable()->default(null);
            $table->string('custom2', 255)->nullable()->default(null);
            $table->string('custom3', 255)->nullable()->default(null);
            $table->string('custom4', 255)->nullable()->default(null);
            $table->string('custom5', 255)->nullable()->default(null);
            $table->mediumInteger('isChargedback')->nullable()->default(null);
            $table->string('chargebackAmount', 255)->nullable()->default(null);
            $table->string('chargebackDate', 255)->nullable()->default(null);
            $table->string('chargebackReasonCode', 255)->nullable()->default(null);
            $table->string('chargebackNote', 255)->nullable()->default(null);
            $table->string('refundReason', 255)->nullable()->default(null);
            $table->string('currencyCode', 255)->nullable()->default(null);
            $table->string('currencySymbol', 255)->nullable()->default(null);
            $table->string('paySourceId', 255)->nullable()->default(null);
            $table->string('mcc', 255)->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_transactions');
    }
};
