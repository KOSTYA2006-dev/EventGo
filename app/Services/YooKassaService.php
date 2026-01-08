<?php

namespace App\Services;

use App\Models\Order;
use YooKassa\Client;
use YooKassa\Model\Notification\NotificationSucceeded;
use YooKassa\Model\Notification\NotificationCanceled;
use YooKassa\Model\NotificationEventType;
use YooKassa\Model\PaymentStatus;
use Illuminate\Support\Facades\Log;

class YooKassaService
{
    private $client;

    public function __construct()
    {
        $shopId = config('payment.yookassa.shop_id');
        $secretKey = config('payment.yookassa.secret_key');

        if (!$shopId || !$secretKey) {
            throw new \Exception('ЮKassa не настроен. Укажите YOOKASSA_SHOP_ID и YOOKASSA_SECRET_KEY в .env');
        }

        $this->client = new Client();
        $this->client->setAuth($shopId, $secretKey);
    }

    /**
     * Создание платежа в ЮKassa
     */
    public function createPayment(Order $order, $returnUrl = null)
    {
        try {
            $payment = $this->client->createPayment(
                [
                    'amount' => [
                        'value' => number_format($order->total_amount, 2, '.', ''),
                        'currency' => 'RUB',
                    ],
                    'confirmation' => [
                        'type' => 'redirect',
                        'return_url' => $returnUrl ?: route('order.success', $order->id),
                    ],
                    'capture' => true,
                    'description' => "Оплата заказа №{$order->order_number}",
                    'metadata' => [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                    ],
                ],
                uniqid('', true)
            );

            // Сохраняем payment_id в заказ
            $order->update([
                'payment_id' => $payment->getId(),
            ]);

            return $payment;
        } catch (\Exception $e) {
            Log::error('Ошибка создания платежа ЮKassa: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Получение информации о платеже
     */
    public function getPaymentInfo($paymentId)
    {
        try {
            return $this->client->getPaymentInfo($paymentId);
        } catch (\Exception $e) {
            Log::error('Ошибка получения информации о платеже: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Обработка webhook от ЮKassa
     */
    public function handleWebhook($requestBody)
    {
        try {
            $notification = json_decode($requestBody, true);
            
            if (!isset($notification['event'])) {
                Log::warning('Webhook без event: ' . $requestBody);
                return null;
            }

            $event = $notification['event'];
            $payment = $notification['object'] ?? null;

            if (!$payment || !isset($payment['id'])) {
                Log::warning('Webhook без payment: ' . $requestBody);
                return null;
            }

            $paymentId = $payment['id'];
            $status = $payment['status'] ?? null;
            $metadata = $payment['metadata'] ?? [];

            // Находим заказ по payment_id или order_number
            $order = null;
            if (isset($metadata['order_id'])) {
                $order = Order::find($metadata['order_id']);
            } elseif (isset($metadata['order_number'])) {
                $order = Order::where('order_number', $metadata['order_number'])->first();
            } else {
                $order = Order::where('payment_id', $paymentId)->first();
            }

            if (!$order) {
                Log::warning("Заказ не найден для payment_id: {$paymentId}");
                return null;
            }

            // Обрабатываем события
            if ($event === NotificationEventType::PAYMENT_SUCCEEDED) {
                if ($status === PaymentStatus::SUCCEEDED && $order->payment_status !== 'paid') {
                    return [
                        'order' => $order,
                        'status' => 'paid',
                        'payment_id' => $paymentId,
                    ];
                }
            } elseif ($event === NotificationEventType::PAYMENT_CANCELED) {
                if ($status === PaymentStatus::CANCELED) {
                    return [
                        'order' => $order,
                        'status' => 'cancelled',
                        'payment_id' => $paymentId,
                    ];
                }
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Ошибка обработки webhook ЮKassa: ' . $e->getMessage());
            return null;
        }
    }
}


