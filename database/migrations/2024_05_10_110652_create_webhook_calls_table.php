<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('webhook_calls', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index()->default('default');
            $table->string('url');
            $table->json('headers')->nullable();
            $table->json('payload')->nullable();
            $table->text('exception')->nullable();
            $table->string('ethoca_id', 25)->index()->nullable()->default(null)->comment('Ethoca generated unique ID for the alert');
            $table->boolean('is_success')->default(false);
            $table->timestamps();
        });
    }
};
