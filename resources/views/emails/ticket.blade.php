<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .ticket {
            background: white;
            border: 3px solid #00ff41;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        .ticket-header {
            text-align: center;
            border-bottom: 2px solid #00ff41;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        .ticket-header h1 {
            color: #000;
            font-size: 28px;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .ticket-number {
            color: #00ff41;
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
        }
        .ticket-body {
            padding: 20px 0;
        }
        .ticket-section {
            margin-bottom: 25px;
        }
        .ticket-section h3 {
            color: #000;
            font-size: 16px;
            text-transform: uppercase;
            border-bottom: 1px solid #ddd;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .ticket-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px dotted #ddd;
        }
        .ticket-row:last-child {
            border-bottom: none;
        }
        .ticket-row strong {
            color: #000;
        }
        .ticket-qr {
            text-align: center;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 10px;
            margin: 20px 0;
        }
        .ticket-qr img {
            max-width: 200px;
            border: 2px solid #00ff41;
            padding: 10px;
            background: white;
        }
        .ticket-footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 2px solid #00ff41;
            color: #666;
            font-size: 14px;
        }
        .ticket-barcode {
            font-family: 'Courier New', monospace;
            font-size: 24px;
            letter-spacing: 3px;
            text-align: center;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="ticket">
            <div class="ticket-header">
                <h1>Ваш билет</h1>
                <div class="ticket-number">№{{ $order->order_number }}</div>
            </div>

            <div class="ticket-body">
                <div class="ticket-section">
                    <h3>Информация о мероприятии</h3>
                    <div class="ticket-row">
                        <strong>Название билета:</strong>
                        <span>{{ $order->ticket->name }}</span>
                    </div>
                    <div class="ticket-row">
                        <strong>Тип:</strong>
                        <span>{{ $order->ticket->type === 'vip' ? 'VIP' : 'Обычный' }}</span>
                    </div>
                    <div class="ticket-row">
                        <strong>Количество:</strong>
                        <span>{{ $order->quantity }} шт.</span>
                    </div>
                </div>

                <div class="ticket-section">
                    <h3>Информация о покупателе</h3>
                    <div class="ticket-row">
                        <strong>ФИО:</strong>
                        <span>{{ $order->customer->last_name }} {{ $order->customer->first_name }}</span>
                    </div>
                    <div class="ticket-row">
                        <strong>Email:</strong>
                        <span>{{ $order->customer->email }}</span>
                    </div>
                    <div class="ticket-row">
                        <strong>Телефон:</strong>
                        <span>{{ $order->customer->phone }}</span>
                    </div>
                </div>

                @if($order->promoCode)
                <div class="ticket-section">
                    <h3>Промокод</h3>
                    <div class="ticket-row">
                        <strong>Код:</strong>
                        <span>{{ $order->promoCode->code }}</span>
                    </div>
                </div>
                @endif

                <div class="ticket-barcode">
                    {{ strtoupper($order->order_number) }}
                </div>

                <div class="ticket-footer">
                    <p><strong>Сохраните этот билет!</strong></p>
                    <p>Предъявите его при входе на мероприятие</p>
                    <p style="margin-top: 15px; color: #999; font-size: 12px;">
                        Дата заказа: {{ $order->created_at->format('d.m.Y H:i') }}
                    </p>
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 20px; color: #666; font-size: 14px;">
            <p>С уважением,<br>Команда EventGo</p>
            <p style="margin-top: 10px;">
                Если у вас возникли вопросы, свяжитесь с нами по email: 
                <a href="mailto:support@eventgo.ru" style="color: #00ff41;">support@eventgo.ru</a>
            </p>
        </div>
    </div>
</body>
</html>


