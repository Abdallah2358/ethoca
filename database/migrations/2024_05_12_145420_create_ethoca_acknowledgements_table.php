<?php

use App\Models\EthocaAlert;
use App\Models\EthocaRequest;
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
        Schema::create('ethoca_acknowledgements', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(EthocaAlert::class)->comment('Alert id that this acknowledgement is related to');
            $table->foreignIdFor(EthocaRequest::class)->comment('Request id that is held this acknowledgement');
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
