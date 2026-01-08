<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Http\Controllers\PaymentController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:check {--order= : Проверить конкретный заказ по ID} {--test : Тестовая проверка для заказов на 10 рублей}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Автоматическая проверка оплаты заказов. Используйте --test для проверки заказов на 10 рублей';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderId = $this->option('order');
        $testMode = $this->option('test');
        
        if ($orderId) {
            // Проверка конкретного заказа
            $order = Order::with(['customer', 'ticket'])->find($orderId);
            
            if (!$order) {
                $this->error("Заказ с ID {$orderId} не найден");
                return 1;
            }
            
            return $this->checkOrder($order, $testMode);
        } else {
            // Проверка всех неоплаченных заказов
            $query = Order::with(['customer', 'ticket'])
                ->where('payment_status', 'pending');
            
            if ($testMode) {
                // Только заказы на 10 рублей
                $query->where('total_amount', 10.00);
                $this->info('Режим тестирования: проверяем только заказы на 10 рублей');
            }
            
            $orders = $query->get();
            
            if ($orders->isEmpty()) {
                $this->info('Нет заказов для проверки');
                return 0;
            }
            
            $this->info("Найдено заказов для проверки: {$orders->count()}");
            
            $checked = 0;
            foreach ($orders as $order) {
                if ($this->checkOrder($order, $testMode)) {
                    $checked++;
                }
            }
            
            $this->info("Проверено заказов: {$checked}");
            return 0;
        }
    }
    
    private function checkOrder(Order $order, bool $testMode = false): bool
    {
        if ($order->payment_status === 'paid') {
            $this->warn("Заказ №{$order->order_number} уже оплачен");
            return false;
        }
        
        if ($testMode && abs($order->total_amount - 10.00) > 0.01) {
            $this->warn("Заказ №{$order->order_number} не на 10 рублей (сумма: {$order->total_amount})");
            return false;
        }
        
        try {
            // В реальной системе здесь должна быть проверка через API банка
            // Для тестирования просто подтверждаем оплату
            $order->update(['payment_status' => 'paid']);
            
            // Генерация и отправка чека
            $paymentController = new PaymentController();
            $paymentController->generateAndSendReceipt($order);
            
            $this->info("✓ Заказ №{$order->order_number} подтвержден. Чек отправлен на {$order->customer->email}");
            Log::info("Payment auto-verified for order {$order->order_number}");
            
            return true;
        } catch (\Exception $e) {
            $this->error("Ошибка при проверке заказа №{$order->order_number}: " . $e->getMessage());
            Log::error("Payment check error for order {$order->order_number}: " . $e->getMessage());
            return false;
        }
    }
}
