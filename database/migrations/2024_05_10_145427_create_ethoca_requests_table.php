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
            $table->string('alert_type')->comment('The type of the alert');
            $table->date('search_start_date')->comment('The start date of the search');
            $table->date('search_end_date')->comment('The end date of the search');
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
