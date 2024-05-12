<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ethoca_alert_responses', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('major_code')->comment('The code indicating the outcome of the request.');
            $table->string('status')->comment('The status indicates one of the following 3: Success, Continue or Fail');
            $table->tinyInteger('number_of_alerts')->comment('The number of alerts returned in the request.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ethoca_alert_responses');
    }
};
