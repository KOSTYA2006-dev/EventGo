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
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Промокод
            $table->enum('discount_type', ['percentage', 'fixed']); // Тип скидки (процент или фиксированная)
            $table->decimal('discount_value', 10, 2); // Значение скидки
            $table->integer('max_uses')->nullable(); // Максимальное количество использований
            $table->integer('used_count')->default(0); // Количество использований
            $table->dateTime('valid_from')->nullable(); // Действителен с
            $table->dateTime('valid_until')->nullable(); // Действителен до
            $table->boolean('is_active')->default(true); // Активен ли промокод
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_codes');
    }
};
