<?php

use App\Models\EthocaAlertResponse;
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
        Schema::create('ethoca_alert_errors', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(EthocaAlertResponse::class)->comment('Associated Alert Response ID');
            $table->string('code', 10)->comment('The error code returned by Ethoca');
            $table->string('description', 255)->comment('The error message returned by Ethoca');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ethoca_alert_errors');
    }
};
