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
        Schema::create('ethoca_requests', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('The title give to the request');
            $table->unsignedTinyInteger('ethoca_fun_code')->comment('1 - Alerts , 2 - Ack , 3 - Update')->index();
            $table->string('alert_type')->comment('The type of the alert')->nullable()->default(null);
            $table->date('search_start_date')->comment('The start date of the search')->nullable()->default(null);
            $table->date('search_end_date')->comment('The end date of the search')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ethoca_requests');
    }
};
