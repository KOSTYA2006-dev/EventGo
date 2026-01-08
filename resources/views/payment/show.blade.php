<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оплата заказа №{{ $order->order_number }}</title>
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
            max-width: 900px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .payment-card {
            background: rgba(0, 0, 0, 0.9);
            border: 2px solid #00ff41;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 0 30px rgba(0, 255, 65, 0.3);
            backdrop-filter: blur(10px);
        }

        .order-info {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 2px solid #00ff41;
        }

        .order-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: #00ff41;
            text-shadow: 0 0 10px #00ff41;
            margin-bottom: 1rem;
            letter-spacing: 2px;
        }

        .order-amount {
            font-size: 3rem;
            font-weight: bold;
            color: #00ff41;
            text-shadow: 0 0 20px #00ff41;
            margin-bottom: 0.5rem;
        }

        .order-info p {
            color: rgba(0, 255, 65, 0.8);
            font-size: 1rem;
        }

        .payment-method-section {
            margin-bottom: 2rem;
        }

        .payment-tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .payment-tab {
            flex: 1;
            padding: 1rem;
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
        }

        .payment-tab:hover {
            background: rgba(0, 255, 65, 0.2);
            box-shadow: 0 0 15px rgba(0, 255, 65, 0.5);
        }

        .payment-tab.active {
            background: rgba(0, 255, 65, 0.3);
            box-shadow: 0 0 20px rgba(0, 255, 65, 0.7);
        }

        .payment-content {
            display: none;
        }

        .payment-content.active {
            display: block;
        }

        .qr-code-container {
            text-align: center;
            padding: 2rem;
            background: rgba(0, 255, 65, 0.05);
            border: 1px solid #00ff41;
            border-radius: 10px;
        }

        .qr-code-container h3 {
            color: #00ff41;
            text-shadow: 0 0 10px #00ff41;
            margin-bottom: 1.5rem;
            font-size: 1.25rem;
        }

        .qr-code-container img {
            max-width: 300px;
            width: 100%;
            border: 2px solid #00ff41;
            border-radius: 10px;
            padding: 1rem;
            background: #000;
            box-shadow: 0 0 20px rgba(0, 255, 65, 0.5);
        }

        .qr-code-container p {
            color: rgba(0, 255, 65, 0.8);
            margin-top: 1rem;
            font-size: 0.875rem;
        }

        .sbp-info {
            padding: 2rem;
            background: rgba(0, 255, 65, 0.05);
            border: 1px solid #00ff41;
            border-radius: 10px;
        }

        .sbp-info h3 {
            color: #00ff41;
            text-shadow: 0 0 10px #00ff41;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .sbp-info p {
            color: rgba(0, 255, 65, 0.8);
            margin-bottom: 1.5rem;
        }

        .bank-details {
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid #00ff41;
            border-radius: 5px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .bank-details-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(0, 255, 65, 0.3);
            color: #00ff41;
        }

        .bank-details-row:last-child {
            border-bottom: none;
        }

        .bank-details-row span:first-child {
            font-weight: bold;
            text-shadow: 0 0 5px #00ff41;
        }

        .copy-btn {
            width: 100%;
            padding: 1rem;
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
        }

        .copy-btn:hover {
            background: rgba(0, 255, 65, 0.2);
            box-shadow: 0 0 20px rgba(0, 255, 65, 0.5);
        }

        .payment-details {
            background: rgba(0, 255, 65, 0.05);
            border: 1px solid #00ff41;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .payment-details-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(0, 255, 65, 0.3);
            color: #00ff41;
        }

        .payment-details-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 1.25rem;
            margin-top: 0.5rem;
            padding-top: 1rem;
            text-shadow: 0 0 10px #00ff41;
        }

        .btn {
            width: 100%;
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
            display: inline-block;
            text-align: center;
            margin-top: 1rem;
        }

        .btn:hover {
            background: rgba(0, 255, 65, 0.2);
            box-shadow: 0 0 30px rgba(0, 255, 65, 0.5);
            transform: scale(1.02);
        }

        .btn-success {
            background: rgba(0, 255, 65, 0.2);
            box-shadow: 0 0 15px rgba(0, 255, 65, 0.3);
        }

        .btn-success:hover {
            background: rgba(0, 255, 65, 0.3);
            box-shadow: 0 0 30px rgba(0, 255, 65, 0.7);
        }

        @media (max-width: 768px) {
            .payment-tabs {
                flex-direction: column;
            }

            .qr-code-container img {
                max-width: 250px;
            }
        }
    </style>
</head>
<body>
    <canvas id="c"></canvas>
    <div id="matrix-background"></div>

    <div class="content-wrapper">
        <div class="container">
            <div class="payment-card">
                <div class="order-info">
                    <div class="order-number">ЗАКАЗ №{{ $order->order_number }}</div>
                    <div class="order-amount">{{ $order->formatted_total_amount }}</div>
                    <p>Оплатите заказ удобным способом</p>
                </div>

                <div class="payment-method-section">
                    <div class="payment-tabs">
                        <button class="payment-tab {{ $order->payment_method === 'qr' ? 'active' : '' }}" onclick="switchPaymentMethod('qr')">
                            QR КОД
                        </button>
                        <button class="payment-tab {{ $order->payment_method === 'sbp' ? 'active' : '' }}" onclick="switchPaymentMethod('sbp')">
                            СБП
                        </button>
                    </div>

                    <div id="qr-content" class="payment-content {{ $order->payment_method === 'qr' ? 'active' : '' }}">
                        <div class="qr-code-container">
                            <h3>Отсканируйте QR код для оплаты</h3>
                            <img src="data:image/png;base64,{{ $qrCode }}" alt="QR код для оплаты">
                            <p>Используйте приложение вашего банка для сканирования QR кода</p>
                        </div>
                    </div>

                    <div id="sbp-content" class="payment-content {{ $order->payment_method === 'sbp' ? 'active' : '' }}">
                        <div class="sbp-info">
                            <h3>Оплата через СБП</h3>
                            <p>Переведите средства на наш расчетный счет ВТБ</p>
                            
                            <div class="bank-details">
                                <div class="bank-details-row">
                                    <span>Получатель:</span>
                                    <span id="recipient">{{ config('payment.recipient_name', 'ИП ЛАЗАРЕВА СВЕТЛАНА ИГОРЕВНА') }}</span>
                                </div>
                                <div class="bank-details-row">
                                    <span>ИНН:</span>
                                    <span id="inn">{{ config('payment.vtb_inn', '616404172802') }}</span>
                                </div>
                                <div class="bank-details-row">
                                    <span>Счет:</span>
                                    <span id="account">{{ config('payment.vtb_account', '40802810506640007313') }}</span>
                                </div>
                                <div class="bank-details-row">
                                    <span>Банк:</span>
                                    <span id="bank">{{ config('payment.bank_name', 'ФИЛИАЛ "ЦЕНТРАЛЬНЫЙ" БАНКА ВТБ (ПАО)') }}</span>
                                </div>
                                <div class="bank-details-row">
                                    <span>БИК:</span>
                                    <span id="bik">{{ config('payment.vtb_bik', '044525411') }}</span>
                                </div>
                                <div class="bank-details-row">
                                    <span>Корр. счет:</span>
                                    <span id="corr_account">{{ config('payment.vtb_correspondent_account', '30101810145250000411') }}</span>
                                </div>
                                <div class="bank-details-row">
                                    <span>Сумма:</span>
                                    <span style="text-shadow: 0 0 10px #00ff41; font-weight: bold;">{{ $order->formatted_total_amount }}</span>
                                </div>
                                <div class="bank-details-row">
                                    <span>Назначение:</span>
                                    <span>Оплата заказа №{{ $order->order_number }}</span>
                                </div>
                            </div>
                            
                            <button class="copy-btn" onclick="copyAccount()">Копировать реквизиты</button>
                        </div>
                    </div>
                </div>

                <div class="payment-details">
                    <div class="payment-details-row">
                        <span>Билет:</span>
                        <span>{{ $order->ticket->name }}</span>
                    </div>
                    <div class="payment-details-row">
                        <span>Количество:</span>
                        <span>{{ $order->quantity }} шт.</span>
                    </div>
                    @if($order->promoCode)
                    <div class="payment-details-row">
                        <span>Промокод:</span>
                        <span style="color: #00ff41; text-shadow: 0 0 5px #00ff41;">{{ $order->promoCode->code }}</span>
                    </div>
                    <div class="payment-details-row">
                        <span>Скидка:</span>
                        <span style="color: #00ff41; text-shadow: 0 0 5px #00ff41;">-{{ number_format($order->discount_amount, 2, '.', ' ') }} ₽</span>
                    </div>
                    @endif
                    <div class="payment-details-row">
                        <span>Итого:</span>
                        <span>{{ $order->formatted_total_amount }}</span>
                    </div>
                </div>

                <form action="{{ route('payment.confirm', $order->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        Я оплатил(а) заказ
                    </button>
                </form>

                <a href="{{ route('index') }}" class="btn">
                    Вернуться на главную
                </a>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/backgraund.js') }}"></script>
    <script src="{{ asset('assets/js/matrix.js') }}"></script>
    <script>
        function switchPaymentMethod(method) {
            document.querySelectorAll('.payment-tab').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.payment-content').forEach(content => content.classList.remove('active'));
            
            event.target.classList.add('active');
            document.getElementById(method + '-content').classList.add('active');
        }

        function copyAccount() {
            const recipient = document.getElementById('recipient').textContent;
            const inn = document.getElementById('inn').textContent;
            const account = document.getElementById('account').textContent;
            const bank = document.getElementById('bank').textContent;
            const bik = document.getElementById('bik').textContent;
            const corrAccount = document.getElementById('corr_account').textContent;
            
            const text = `Получатель: ${recipient}\nИНН: ${inn}\nСчет: ${account}\nБанк: ${bank}\nБИК: ${bik}\nКорр. счет: ${corrAccount}`;
            
            navigator.clipboard.writeText(text).then(() => {
                alert('Реквизиты скопированы в буфер обмена');
            }).catch(() => {
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                alert('Реквизиты скопированы в буфер обмена');
            });
        }

    </script>
</body>
</html>
