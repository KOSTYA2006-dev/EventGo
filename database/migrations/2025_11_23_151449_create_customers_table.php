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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name'); // Имя
            $table->string('last_name'); // Фамилия
            $table->string('phone'); // Номер телефона
            $table->string('email'); // Электронная почта
            $table->enum('activity_type', ['podologist', 'aesthetician', 'manager']); // Вид деятельности
            $table->boolean('personal_data_agreement')->default(false); // Согласие на обработку персональных данных
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
