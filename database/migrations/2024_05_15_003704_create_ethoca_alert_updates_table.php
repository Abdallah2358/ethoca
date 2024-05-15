<?php

use App\Models\EthocaAlert;
use App\Models\EthocaAlertResponse;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ethoca_alert_updates', function (Blueprint $table) {
            $table->id();
            // update related felids
            $table->foreignIdFor(EthocaAlert::class)->comment('The alert id');
            // redundant field as we can get the alert id from the alert model
            // but it is used to reduce the number of queries since time is more important than space
            $table->string('ethoca_id', 25)->comment('The Ethoca ID')->nullable()->default(null)->index();

            $table->string('outcome', 50)->comment('The outcome of the alert')->index();
            $table->string('refunded', 50)->comment('The refunded amount')->index(); // can be refactored to tiny int
            $table->boolean('first_party_fraud')->comment('The first party fraud amount')->index();
            $table->decimal('amount_stopped', 10, 2)->comment('The amount stopped');
            $table->dateTime('action_timestamp')->comment('The date and time the transaction was refunded / action taken as a result of the alert.');
            $table->string('update_comment', 250)->comment('Comment for the update - Additional comments to be provided with the
             outcome');
            // $table->boolean('status')->comment('The status of the update 1 - success, 0 - failed');
            // we don't need the status field as we can check the is_sent field and for errors we can check the errors table
            // if we get a status failed from ethoca just keep the update record and log the error in the errors table
            $table->boolean('is_sent')->comment('This flag is raised when the update is sent to ethoca and received a status success from ethoca')->default(0)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ethoca_alert_updates');
    }
};
