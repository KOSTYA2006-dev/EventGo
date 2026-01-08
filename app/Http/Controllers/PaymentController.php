<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\YooKassaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $yooKassaService;

    public function __construct(YooKassaService $yooKassaService)
    {
        $this->yooKassaService = $yooKassaService;
    }

    public function show($id)
    {
        $order = Order::with(['customer', 'ticket', 'promoCode'])->findOrFail($id);
        
        if ($order->payment_status === 'paid') {
            return redirect()->route('order.success', $order->id);
        }

        // Используем YooKassa для оплаты
        try {
            $returnUrl = route('order.success', $order->id);
            $payment = $this->yooKassaService->createPayment($order, $returnUrl);
            
            // Перенаправляем на страницу оплаты YooKassa
            return redirect($payment->getConfirmation()->getConfirmationUrl());
        } catch (\Exception $e) {
            Log::error('Ошибка создания платежа YooKassa: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Ошибка при создании платежа. Попробуйте позже.']);
        }
    }

    public function confirm($id)
    {
        // Этот метод больше не используется, так как оплата происходит через YooKassa
        // Оставлен для обратной совместимости
        return redirect()->route('payment.show', $id);
    }

    public function success($id)
    {
        $order = Order::with(['customer', 'ticket', 'promoCode'])->findOrFail($id);
        return view('order.success', compact('order'));
    }

    /**
     * Тестовый эндпоинт для проверки оплаты (для тестирования на 10 рублей)
     * Используйте этот URL для тестирования: POST /payment/test-check/{order_id}
     * ВНИМАНИЕ: Используйте только для тестирования! В продакшене оплата происходит через YooKassa.
     */
    public function testCheck($id)
    {
        try {
            $order = Order::with(['customer', 'ticket'])->findOrFail($id);
            
            if ($order->payment_status === 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Заказ уже оплачен',
                    'order_number' => $order->order_number
                ]);
            }
            
            // Для тестирования проверяем, что сумма = 10 рублей
            if (abs($order->total_amount - 10.00) > 0.01) {
                return response()->json([
                    'success' => false,
                    'message' => 'Тестовая проверка работает только для заказов на сумму 10 рублей',
                    'order_amount' => $order->total_amount,
                    'required_amount' => 10.00
                ]);
            }
            
            // Обновление статуса оплаты
            $order->update(['payment_status' => 'paid']);
            
            // Автоматическая генерация и отправка чека и билета
            $this->generateAndSendReceipt($order);
            
            Log::info("Test payment verified for order {$order->order_number}");
            
            return response()->json([
                'success' => true,
                'message' => 'Оплата подтверждена. Чек и билет отправлены на email.',
                'order_number' => $order->order_number,
                'customer_email' => $order->customer->email
            ]);
            
        } catch (\Exception $e) {
            Log::error('Test check error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Ошибка при проверке оплаты: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Webhook для автоматической обработки оплаты от YooKassa
     * Настройте этот URL в личном кабинете YooKassa: https://yookassa.ru/my
     * URL: https://ваш-домен.ru/payment/webhook
     */
    public function webhook(Request $request)
    {
        try {
            // Логирование входящего запроса для отладки
            Log::info('YooKassa webhook received', [
                'data' => $request->all(),
                'headers' => $request->headers->all()
            ]);
            
            // Обработка webhook от YooKassa
            $requestBody = $request->getContent();
            $result = $this->yooKassaService->handleWebhook($requestBody);
            
            if (!$result) {
                Log::warning('YooKassa webhook: no action required');
                return response()->json(['status' => 'ok'], 200);
            }
            
            $order = $result['order'];
            $status = $result['status'];
            
            // Если оплата успешна, обновляем статус и отправляем чек и билет
            if ($status === 'paid' && $order->payment_status !== 'paid') {
                $order->update([
                    'payment_status' => 'paid',
                    'payment_id' => $result['payment_id'],
                ]);
                
                // Автоматическая генерация и отправка чека и билета
                $this->generateAndSendReceipt($order);
                
                Log::info("Payment verified via YooKassa webhook for order {$order->order_number}");
                
                return response()->json([
                    'status' => 'ok',
                    'message' => 'Payment processed and emails sent'
                ], 200);
            } elseif ($order->payment_status === 'paid') {
                Log::info("Order {$order->order_number} already paid");
                return response()->json(['status' => 'ok'], 200);
            }
            
            return response()->json(['status' => 'ok'], 200);
            
        } catch (\Exception $e) {
            Log::error('YooKassa webhook error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }


    public function generateAndSendReceipt(Order $order)
    {
        try {
            // Генерация чека
            $receiptPath = $this->generateReceiptPDF($order);
            
            if ($receiptPath) {
                $order->update([
                    'payment_receipt_path' => $receiptPath,
                ]);
            }

            // Генерация билета
            $ticketPath = $this->generateTicketPDF($order);
            
            if ($ticketPath) {
                $order->update([
                    'ticket_path' => $ticketPath,
                ]);
            }

            // Отправка чека на email
            try {
                Mail::send('emails.receipt', ['order' => $order], function ($message) use ($order, $receiptPath) {
                    $message->to($order->customer->email)
                        ->subject('Чек об оплате заказа №' . $order->order_number);
                    
                    if ($receiptPath && Storage::exists($receiptPath)) {
                        $message->attach(Storage::path($receiptPath), [
                            'as' => 'receipt_' . $order->order_number . '.html',
                            'mime' => 'text/html',
                        ]);
                    }
                });
                Log::info("Receipt email sent to {$order->customer->email} for order {$order->order_number}");
            } catch (\Exception $e) {
                Log::error('Ошибка отправки чека на email: ' . $e->getMessage());
            }

            // Отправка билета на email
            try {
                Mail::send('emails.ticket', ['order' => $order], function ($message) use ($order, $ticketPath) {
                    $message->to($order->customer->email)
                        ->subject('Ваш билет - Заказ №' . $order->order_number);
                    
                    if ($ticketPath && Storage::exists($ticketPath)) {
                        $message->attach(Storage::path($ticketPath), [
                            'as' => 'ticket_' . $order->order_number . '.html',
                            'mime' => 'text/html',
                        ]);
                    }
                });
                Log::info("Ticket email sent to {$order->customer->email} for order {$order->order_number}");
            } catch (\Exception $e) {
                Log::error('Ошибка отправки билета на email: ' . $e->getMessage());
            }

            $order->update([
                'receipt_sent' => true,
                'ticket_sent' => true,
            ]);
        } catch (\Exception $e) {
            Log::error('Ошибка при генерации и отправке чека/билета: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }


    private function generateReceiptPDF(Order $order): ?string
    {
        // Генерация HTML чека
        $receiptContent = view('receipt.template', compact('order'))->render();
        
        // Сохраняем как HTML файл (можно конвертировать в PDF используя библиотеку типа dompdf)
        $filename = 'receipts/receipt_' . $order->order_number . '_' . time() . '.html';
        Storage::put($filename, $receiptContent);
        
        return $filename;
    }

    private function generateTicketPDF(Order $order): ?string
    {
        // Генерация HTML билета
        $ticketContent = view('emails.ticket', compact('order'))->render();
        
        // Сохраняем как HTML файл
        $filename = 'tickets/ticket_' . $order->order_number . '_' . time() . '.html';
        Storage::put($filename, $ticketContent);
        
        return $filename;
    }
}
