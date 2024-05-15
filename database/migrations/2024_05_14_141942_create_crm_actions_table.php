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
            $table->foreignIdFor(EthocaAlert::class)->comment('Alert id that is caused this action to trigger');
            $table->string('name')->comment("A Descriptive name for action take on CRM");
            $table->string('link')->comment("Link used to show the action details on CRM");
            $table->string('status')->default('pending')->comment("Status of the action"); // can be a tiny int 
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
