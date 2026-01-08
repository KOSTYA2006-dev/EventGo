<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .details {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        .row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 1.2em;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #6366f1;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Чек об оплате</h1>
            <p>Заказ №{{ $order->order_number }}</p>
        </div>
        <div class="content">
            <p>Здравствуйте, {{ $order->customer->first_name }}!</p>
            <p>Спасибо за покупку. Ваш заказ успешно оплачен.</p>

            <div class="details">
                <h3>Детали заказа:</h3>
                <div class="row">
                    <span>Билет:</span>
                    <span>{{ $order->ticket->name }}</span>
                </div>
                <div class="row">
                    <span>Количество:</span>
                    <span>{{ $order->quantity }} шт.</span>
                </div>
                @if($order->promoCode)
                <div class="row">
                    <span>Промокод:</span>
                    <span>{{ $order->promoCode->code }}</span>
                </div>
                <div class="row">
                    <span>Скидка:</span>
                    <span>-{{ number_format($order->discount_amount, 2, '.', ' ') }} ₽</span>
                </div>
                @endif
                <div class="row">
                    <span>Итого:</span>
                    <span>{{ $order->formatted_total_amount }}</span>
                </div>
            </div>

            <div class="details" style="margin-top: 20px;">
                <h3>Банковские реквизиты:</h3>
                <div class="row">
                    <span>Получатель:</span>
                    <span>{{ config('payment.recipient_name', 'ИП ЛАЗАРЕВА СВЕТЛАНА ИГОРЕВНА') }}</span>
                </div>
                <div class="row">
                    <span>ИНН:</span>
                    <span>{{ config('payment.vtb_inn', '616404172802') }}</span>
                </div>
                <div class="row">
                    <span>Счет:</span>
                    <span>{{ config('payment.vtb_account', '40802810506640007313') }}</span>
                </div>
                <div class="row">
                    <span>Банк:</span>
                    <span>{{ config('payment.bank_name', 'ФИЛИАЛ "ЦЕНТРАЛЬНЫЙ" БАНКА ВТБ (ПАО)') }}</span>
                </div>
                <div class="row">
                    <span>БИК:</span>
                    <span>{{ config('payment.vtb_bik', '044525411') }}</span>
                </div>
                <div class="row" style="border-bottom: none;">
                    <span>Корр. счет:</span>
                    <span>{{ config('payment.vtb_correspondent_account', '30101810145250000411') }}</span>
                </div>
            </div>

            <p>Чек прикреплен к этому письму.</p>
            <p>С уважением,<br>Команда EventGo</p>
        </div>
    </div>
</body>
</html>

