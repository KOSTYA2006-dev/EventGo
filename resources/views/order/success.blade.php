<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оплата успешна - Заказ №{{ $order->order_number }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/main-set.css') }}">
    <style>
        @font-face {
            font-family: 'Ubuntu';
            src: url('{{ asset("assets/text-style/Ubuntu/Ubuntu-Regular.ttf") }}') format('truetype');
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #000;
            color: #00ff41;
            font-family: 'Ubuntu', monospace;
            min-height: 100vh;
            position: relative;
            padding: 2rem 0;
        }

        canvas {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1;
            display: block;
        }

        #matrix-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
        }

        .matrix-column {
            position: absolute;
            top: -100%;
            color: #00ff41;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.2;
            animation: matrix-fall linear infinite;
            text-shadow: 0 0 5px #00ff41;
        }

        @keyframes matrix-fall {
            to { transform: translateY(100vh); }
        }

        .content-wrapper {
            position: relative;
            z-index: 10;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .success-card {
            background: rgba(0, 0, 0, 0.9);
            border: 2px solid #00ff41;
            border-radius: 10px;
            box-shadow: 0 0 30px rgba(0, 255, 65, 0.3);
            padding: 3rem 2rem;
            text-align: center;
            backdrop-filter: blur(10px);
        }

        .success-icon {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                text-shadow: 0 0 20px #00ff41, 0 0 40px #00ff41;
                transform: scale(1);
            }
            50% {
                text-shadow: 0 0 30px #00ff41, 0 0 60px #00ff41, 0 0 80px #00ff41;
                transform: scale(1.1);
            }
        }

        .success-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
            color: #00ff41;
            text-shadow: 0 0 20px #00ff41;
            letter-spacing: 2px;
        }

        .success-message {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            color: rgba(0, 255, 65, 0.9);
        }

        .order-details {
            background: rgba(0, 255, 65, 0.05);
            border: 1px solid #00ff41;
            border-radius: 10px;
            padding: 2rem;
            margin: 2rem 0;
            text-align: left;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(0, 255, 65, 0.3);
            color: #00ff41;
        }

        .detail-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 1.25rem;
            margin-top: 0.5rem;
            padding-top: 1rem;
            text-shadow: 0 0 10px #00ff41;
        }

        .detail-row span:first-child {
            font-weight: bold;
            text-shadow: 0 0 5px #00ff41;
        }

        .info-message {
            background: rgba(0, 255, 65, 0.1);
            border: 1px solid #00ff41;
            border-radius: 5px;
            padding: 1rem;
            margin: 2rem 0;
            color: #00ff41;
        }

        .btn {
            display: inline-block;
            padding: 1rem 2rem;
            background: rgba(0, 255, 65, 0.1);
            border: 2px solid #00ff41;
            border-radius: 5px;
            color: #00ff41;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Ubuntu', monospace;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-decoration: none;
            margin-top: 1rem;
        }

        .btn:hover {
            background: rgba(0, 255, 65, 0.2);
            box-shadow: 0 0 30px rgba(0, 255, 65, 0.5);
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .success-title {
                font-size: 2rem;
            }

            .success-icon {
                font-size: 4rem;
            }

            .order-details {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <canvas id="c"></canvas>
    <div id="matrix-background"></div>

    <div class="content-wrapper">
        <div class="container">
            <div class="success-card">
                <div class="success-icon">✓</div>
                <h1 class="success-title">ОПЛАТА УСПЕШНА</h1>
                <p class="success-message">Ваш заказ успешно оплачен. Чек отправлен на email.</p>

                <div class="order-details">
                    <div class="detail-row">
                        <span>Номер заказа:</span>
                        <span>{{ $order->order_number }}</span>
                    </div>
                    <div class="detail-row">
                        <span>Билет:</span>
                        <span>{{ $order->ticket->name }}</span>
                    </div>
                    <div class="detail-row">
                        <span>Количество:</span>
                        <span>{{ $order->quantity }} шт.</span>
                    </div>
                    @if($order->promoCode)
                    <div class="detail-row">
                        <span>Промокод:</span>
                        <span style="color: #00ff41; text-shadow: 0 0 5px #00ff41;">{{ $order->promoCode->code }}</span>
                    </div>
                    @endif
                    <div class="detail-row">
                        <span>Итого:</span>
                        <span>{{ $order->formatted_total_amount }}</span>
                    </div>
                </div>

                <div class="info-message">
                    <p>Чек об оплате и билет отправлены на email: <strong>{{ $order->customer->email }}</strong></p>
                    <p style="margin-top: 0.5rem; font-size: 0.875rem; opacity: 0.8;">Проверьте папку "Входящие" или "Спам"</p>
                    @if($order->receipt_sent && $order->ticket_sent)
                    <p style="margin-top: 0.5rem; color: #00ff41; font-weight: bold;">✓ Чек и билет успешно отправлены</p>
                    @endif
                </div>

                <a href="{{ route('index') }}" class="btn">ВЕРНУТЬСЯ НА ГЛАВНУЮ</a>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/backgraund.js') }}"></script>
    <script src="{{ asset('assets/js/matrix.js') }}"></script>
</body>
</html>

