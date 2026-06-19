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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->foreignId('order_id')->constrained('orders');
            // на VIN-платформе клиент вводит идентификатор авто — не всегда VIN.
            // Иногда ГРЗ (номер), иногда СТС. В одной позиции заказа нужно сохранить что ввели и какой это тип,
            // чтобы pipeline знал, в какой API идти.
            $table->string('input_type');
            $table->string('input_value');
            $table->decimal('price_snapshot', 10, 2)->unsigned();
            $table->foreignId('service_offer_id')->constrained('service_offers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
