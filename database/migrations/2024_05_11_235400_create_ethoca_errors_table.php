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
        Schema::create('ethoca_errors', function (Blueprint $table) {
            $table->id();
            $table->string('model')->nullable()->default(null)->comment('The model that the error is associated with');
            $table->bigInteger('model_id')->nullable()->default(null)->comment('The id associated with the model related to this error');
            $table->string('ethoca_id', 50)->index()->nullable()->default(null)->comment('The error type');
            $table->string('code', 10)->comment('The error code returned by Ethoca');
            $table->string('description', 255)->comment('The error message returned by Ethoca');
            $table->string('notes', 255)->comment('The some notes about the error')
                ->nullable()->default(null);
            $table->boolean('is_ack_error')->comment('The flag to indicate if the error is an acknowledgement error specific for alerts')
                ->default(0)->index();
            $table->json('data')->nullable()->default(null)->comment('The error data returned by Ethoca');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ethoca_errors');
    }
};
