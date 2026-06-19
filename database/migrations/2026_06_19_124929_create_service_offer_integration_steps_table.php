<?php

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
        Schema::create('service_offer_integration_steps', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('service_offer_id')->constrained('service_offers');
            $table->string('provider');
            $table->integer('step_order');
            $table->string('document_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_offer_integration_steps');
    }
};
