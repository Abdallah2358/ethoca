<?php

use App\Models\CrmTransaction;
use App\Models\EthocaResponse;
use App\Models\Merchant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\WebhookClient\Models\WebhookCall;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ethoca_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(EthocaResponse::class)->comment('Associated Response ID')->nullable()->default(null);
            $table->foreignIdFor(WebhookCall::class)->comment('Associated WebHookCall')->nullable()->default(null);
            $table->foreignIdFor(CrmTransaction::class)->comment('CRM Transaction ID')->index()->nullable()->default(null);
            $table->foreignIdFor(Merchant::class);
            $table->boolean('is_handled')->index()->default(false)->comment('This flag is raised when the CRM is done handling this alert and waiting update');
            $table->boolean('is_paid')->default(false)->index()->comment('This flag is raised when the alert is successfully paid');
            $table->boolean('is_updated')->default(false)->index()->comment('This flag is raised when the alert is successfully state updated with ethoca'); # TODO : Check if it needs to be an enum instead
            $table->boolean('is_ack')->default(false)->index()->comment('This flag is raised when the alert is successfully Acknowledged with ethoca'); # TODO : Check if it needs to be an enum instead
            $table->string('ethoca_id', 25)->comment('Ethoca generated unique ID for the alert')->index()->nullable()->default(null);
            $table->string('type', 30)->comment('The alert type: sourced from issuer/cardscheme or dispute');
            $table->dateTime('alert_timestamp', 3)->comment('The date and time the alert was available to send in the member’s time zone');
            $table->string('age', 50)->comment('Numeric age of the alert. It is the number of hours between the transaction date/time (authorisation date/time) and the AlertTimestamp');
            $table->string('issuer', 100)->comment('The name of the card issuer');
            $table->string('card_number', 19)->comment('Numeric card number. This will be provided unmasked / masked depending on the member’s Ethoca account settings.');
            $table->string('card_bin', 6)->comment('The first 6 digits of the card number');
            $table->string('card_last4', 4)->comment('The last 4 digits of the card number');
            $table->string('arn', 64)->comment('Acquirer Reference Number (ARN) isa unique 23/24-digit number that tagsa card transaction when it goes fromthe merchant\'s bank (the acquiringbank) through the card scheme to thecardholder\'s bank (the issuer). It isonly available for settled transactions');
            $table->dateTime('transaction_timestamp')->comment('This is the authorization date/time for the transaction. YYYY-MM-DDThh:mm:ss in the member’s time zone (e.g. in EST)');
            $table->string('merchant_descriptor', 50)->comment('The merchant descriptor / name as seen on the card holder’s statement');
            $table->integer('member_id')->comment('A unique ID for each Ethoca member. For partners receiving alerts on behalf of merchants then this will be the MemberID for the merchant');
            $table->string('mcc', 4)->comment('Merchant Category Code - defines the industry to which the transaction belongs');
            $table->decimal('amount', 10, 2)->comment('Transaction amount');
            $table->string('currency', 3)->comment('Currency for the transaction amount');
            $table->string('transaction_type')->comment('The type of transaction or POS type');
            $table->string('initiated_by')->comment('Whether the transaction was initially identified by the card holder or the card issuer');
            $table->string('is_3d_secure')->comment('Whether it is a 3D Secure transaction or not.');
            $table->string('source', 50)->comment('This field indicates whether an alert was originated directly from an issuer, or from another source');
            $table->string('auth_code', 8)->comment('Authorization code for this alert');
            $table->string('merchant_member_name', 60)->comment('The merchant name of the Ethoca member.');
            $table->string('transaction_id', 65)->comment('A unique reference number must be flowed throughout the lifecycle of all card transactions.');
            $table->string('chargeback_reason_code', 30)->comment('The card scheme-specific chargeback reason code which indicates the cardholder’s reason for disputing the transaction.');
            $table->decimal('chargeback_amount', 10, 2)->comment('Chargeback Amount');
            $table->string('chargeback_currency', 3)->comment('Currency for the chargeback currency.');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ethoca_alerts');
    }
};
