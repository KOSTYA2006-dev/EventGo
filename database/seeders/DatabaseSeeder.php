<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Ticket;
use App\Models\PromoCode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Создание администратора
        Admin::create([
            'name' => 'Администратор',
            'email' => 'admin@eventgo.ru',
            'password' => Hash::make('admin123'),
        ]);

        // Создание билетов
        Ticket::create([
            'name' => 'Обычный билет',
            'type' => 'regular',
            'price' => 5000.00,
            'description' => 'Стандартный билет на мероприятие с доступом ко всем основным активностям',
            'available_quantity' => 100,
            'is_active' => true,
        ]);

        Ticket::create([
            'name' => 'VIP билет',
            'type' => 'vip',
            'price' => 15000.00,
            'description' => 'VIP билет с расширенными возможностями, приоритетным доступом и дополнительными привилегиями',
            'available_quantity' => 50,
            'is_active' => true,
        ]);

        // Создание промокодов
        PromoCode::create([
            'code' => 'WELCOME10',
            'discount_type' => 'percentage',
            'discount_value' => 10.00,
            'max_uses' => 100,
            'used_count' => 0,
            'valid_from' => now(),
            'valid_until' => now()->addMonths(3),
            'is_active' => true,
        ]);

        PromoCode::create([
            'code' => 'VIP500',
            'discount_type' => 'fixed',
            'discount_value' => 500.00,
            'max_uses' => 50,
            'used_count' => 0,
            'valid_from' => now(),
            'valid_until' => now()->addMonths(2),
            'is_active' => true,
        ]);

        PromoCode::create([
            'code' => 'EARLY20',
            'discount_type' => 'percentage',
            'discount_value' => 20.00,
            'max_uses' => 30,
            'used_count' => 0,
            'valid_from' => now(),
            'valid_until' => now()->addMonth(),
            'is_active' => true,
        ]);
    }
}
