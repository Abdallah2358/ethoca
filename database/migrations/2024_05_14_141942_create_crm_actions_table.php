<?php

use App\Models\EthocaAlert;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('crm_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(EthocaAlert::class)->constrained()->comment('Alert id that is caused this action to trigger');
            $table->tinyInteger('code')->nullable()->default(null)->index()->comment("Crm Action Code For This App"); // can be a tiny int
            $table->string('name')->comment("A Descriptive name for action take on CRM");
            $table->string('link')->nullable()->default(null)->comment("Link used to show the action details on CRM");
            $table->string('status')->default('pending')->comment("Status of the action"); // can be a tiny int
            $table->string('result')->nullable()->default(null)->comment("Status of the action"); // can be a tiny int
            $table->json('data')->nullable()->default(null)->comment("Data related to the action");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_actions');
    }
};
