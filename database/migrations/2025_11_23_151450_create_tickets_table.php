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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Название типа билета
            $table->enum('type', ['regular', 'vip']); // Тип билета
            $table->decimal('price', 10, 2); // Цена
            $table->text('description')->nullable(); // Описание
            $table->integer('available_quantity')->default(0); // Доступное количество
            $table->boolean('is_active')->default(true); // Активен ли билет
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
