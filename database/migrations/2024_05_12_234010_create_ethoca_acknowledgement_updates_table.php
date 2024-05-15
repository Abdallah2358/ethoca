<?php

use App\Models\EthocaAcknowledgementResponse;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ethoca_acknowledgement_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(EthocaAcknowledgementResponse::class)->comment('Associated Acknowledgement Response ID');
            $table->string('ethoca_id',25)->comment('Unique Ethoca ID for the alert updated.');
            $table->string('status', 10)->comment('The code indicating the outcome of the request');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ethoca_acknowledgement_updates');
    }
};
