<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Чек об оплате - Заказ №{{ $order->order_number }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/receipt.css') }}">
    
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h1>ЧЕК ОБ ОПЛАТЕ</h1>
            <div class="order-number">Заказ №{{ $order->order_number }}</div>
            <div style="margin-top: 10px; color: #666; font-size: 14px;">
                Дата: {{ $order->created_at->format('d.m.Y H:i') }}
            </div>
        </div>

        <div class="section">
            <div class="section-title">ИНФОРМАЦИЯ О ПОКУПАТЕЛЕ</div>
            <div class="info-row">
                <span class="info-label">Фамилия:</span>
                <span class="info-value">{{ $order->customer->last_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Имя:</span>
                <span class="info-value">{{ $order->customer->first_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Телефон:</span>
                <span class="info-value">{{ $order->customer->phone }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $order->customer->email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Вид деятельности:</span>
                <span class="info-value">{{ $order->customer->activity_type_label }}</span>
            </div>
        </div>

        <div class="section">
            <div class="section-title">ДЕТАЛИ ЗАКАЗА</div>
            <div class="info-row">
                <span class="info-label">Билет:</span>
                <span class="info-value">{{ $order->ticket->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Тип:</span>
                <span class="info-value">
                    <span class="badge {{ $order->ticket->type === 'vip' ? 'badge-vip' : 'badge-regular' }}">
                        {{ $order->ticket->type === 'vip' ? 'VIP' : 'Обычный' }}
                    </span>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Количество:</span>
                <span class="info-value">{{ $order->quantity }} шт.</span>
            </div>
            <div class="info-row">
                <span class="info-label">Цена за единицу:</span>
                <span class="info-value">{{ number_format($order->ticket_price, 2, '.', ' ') }} ₽</span>
            </div>
@if($order->promoCode)
            <div class="info-row">
                <span class="info-label">Промокод:</span>
                <span class="info-value" style="color: #10b981; font-weight: bold;">{{ $order->promoCode->code }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Скидка:</span>
                <span class="info-value" style="color: #10b981; font-weight: bold;">-{{ number_format($order->discount_amount, 2, '.', ' ') }} ₽</span>
            </div>
@endif
        </div>

        <div class="section">
            <div class="section-title">ОПЛАТА</div>
            <div class="info-row">
                <span class="info-label">Способ оплаты:</span>
                <span class="info-value">{{ $order->payment_method_label }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Статус:</span>
                <span class="info-value">
                    <span class="badge badge-success">{{ $order->payment_status_label }}</span>
                </span>
            </div>
        </div>

        <div class="section">
            <div class="section-title">БАНКОВСКИЕ РЕКВИЗИТЫ</div>
            <div class="info-row">
                <span class="info-label">Получатель:</span>
                <span class="info-value">{{ config('payment.recipient_name', 'ИП ЛАЗАРЕВА СВЕТЛАНА ИГОРЕВНА') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">ИНН:</span>
                <span class="info-value">{{ config('payment.vtb_inn', '616404172802') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Счет:</span>
                <span class="info-value">{{ config('payment.vtb_account', '40802810506640007313') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Банк:</span>
                <span class="info-value">{{ config('payment.bank_name', 'ФИЛИАЛ "ЦЕНТРАЛЬНЫЙ" БАНКА ВТБ (ПАО)') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">БИК:</span>
                <span class="info-value">{{ config('payment.vtb_bik', '044525411') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Корр. счет:</span>
                <span class="info-value">{{ config('payment.vtb_correspondent_account', '30101810145250000411') }}</span>
            </div>
        </div>

        <div class="total-row">
            <span class="total-label">ИТОГО К ОПЛАТЕ:</span>
            <span class="total-value">{{ $order->formatted_total_amount }}</span>
        </div>

        <div class="footer">
            <p>Спасибо за покупку!</p>
            <p style="margin-top: 10px;">EventGo</p>
        </div>
    </div>
</body>
</html>
