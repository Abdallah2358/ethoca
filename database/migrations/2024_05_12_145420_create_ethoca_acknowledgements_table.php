<?php

use App\Models\EthocaAlert;
use App\Models\EthocaRequest;
use App\Models\EthocaResponse;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ethoca_acknowledgements', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(EthocaAlert::class)->comment('Alert id that this acknowledgement is related to');
            $table->foreignIdFor(EthocaRequest::class)->comment('Request id that is sent this acknowledgement to ethoca');
            $table->foreignIdFor(EthocaResponse::class)->nullable()->default(null)->comment('Response id that is confirmed this acknowledgement to status');
            $table->string('ethoca_id', 25)->comment('Ethoca id of the acknowledgement')->nullable()->index();
            $table->string('status')->comment('Status of the acknowledgement');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ethoca_acknowledgements');
    }
};
