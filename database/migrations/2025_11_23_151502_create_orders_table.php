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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // Номер заказа
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade'); // Покупатель
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade'); // Билет
            $table->integer('quantity')->default(1); // Количество билетов
            $table->decimal('ticket_price', 10, 2); // Цена билета
            $table->foreignId('promo_code_id')->nullable()->constrained('promo_codes')->onDelete('set null'); // Промокод
            $table->decimal('discount_amount', 10, 2)->default(0); // Сумма скидки
            $table->decimal('total_amount', 10, 2); // Итоговая сумма
            $table->enum('payment_method', ['qr', 'sbp']); // Способ оплаты
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'cancelled'])->default('pending'); // Статус оплаты
            $table->string('payment_receipt_path')->nullable(); // Путь к чеку
            $table->boolean('receipt_sent')->default(false); // Отправлен ли чек
            $table->text('notes')->nullable(); // Заметки
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
