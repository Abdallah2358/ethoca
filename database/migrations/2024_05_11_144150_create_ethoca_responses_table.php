<?php

use App\Models\EthocaRequest;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ethoca_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(EthocaRequest::class)->comment('Request id that is caused this response to trigger');
            $table->integer('major_code')->comment('Major code of the response');
            $table->string('status')->comment('Status of the response');
            $table->tinyInteger('number_of_alerts')->comment('The number of alerts returned in the request.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ethoca_responses');
    }
};
