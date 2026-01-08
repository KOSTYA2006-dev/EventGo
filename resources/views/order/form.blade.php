<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оформление заказа - {{ $ticket->name }}</title>
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

        .order-card {
            background: rgba(0, 0, 0, 0.9);
            border: 2px solid #00ff41;
            border-radius: 10px;
            box-shadow: 0 0 30px rgba(0, 255, 65, 0.3);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .order-header {
            background: rgba(0, 255, 65, 0.1);
            border-bottom: 2px solid #00ff41;
            padding: 2rem;
            text-align: center;
        }

        .order-header h1 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #00ff41;
            text-shadow: 0 0 10px #00ff41;
            letter-spacing: 2px;
        }

        .order-header .ticket-type {
            display: inline-block;
            background: rgba(0, 255, 65, 0.2);
            border: 1px solid #00ff41;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            color: #00ff41;
            text-shadow: 0 0 5px #00ff41;
        }

        .order-content {
            padding: 2rem;
        }

        .ticket-summary {
            background: rgba(0, 255, 65, 0.05);
            border: 1px solid #00ff41;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .ticket-summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(0, 255, 65, 0.3);
            color: #00ff41;
        }

        .ticket-summary-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 1.25rem;
            margin-top: 0.5rem;
            padding-top: 1rem;
            text-shadow: 0 0 10px #00ff41;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #00ff41;
            text-shadow: 0 0 5px #00ff41;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #00ff41;
            border-radius: 5px;
            font-size: 1rem;
            font-family: 'Ubuntu', monospace;
            background: rgba(0, 0, 0, 0.8);
            color: #00ff41;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            box-shadow: 0 0 15px rgba(0, 255, 65, 0.5);
            border-color: #00ff41;
        }

        .form-group input::placeholder {
            color: rgba(0, 255, 65, 0.5);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        .promo-code-section {
            background: rgba(0, 255, 65, 0.05);
            border: 1px solid #00ff41;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .promo-code-input-group {
            display: flex;
            gap: 0.5rem;
        }

        .promo-code-input-group input {
            flex: 1;
        }

        .promo-code-result {
            margin-top: 0.75rem;
            padding: 0.75rem;
            border-radius: 5px;
            font-size: 0.875rem;
            display: none;
            border: 1px solid;
        }

        .promo-code-result.success {
            background: rgba(0, 255, 65, 0.1);
            color: #00ff41;
            border-color: #00ff41;
            display: block;
        }

        .promo-code-result.error {
            background: rgba(255, 0, 0, 0.1);
            color: #ff0000;
            border-color: #ff0000;
            display: block;
        }

        .payment-method-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .payment-method-option {
            position: relative;
        }

        .payment-method-option input[type="radio"] {
            position: absolute;
            opacity: 0;
        }

        .payment-method-option label {
            display: block;
            padding: 1rem;
            border: 2px solid #00ff41;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: bold;
            background: rgba(0, 255, 65, 0.1);
            color: #00ff41;
            text-shadow: 0 0 5px #00ff41;
        }

        .payment-method-option input[type="radio"]:checked + label {
            background: rgba(0, 255, 65, 0.2);
            box-shadow: 0 0 20px rgba(0, 255, 65, 0.5);
        }

        .checkbox-group {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .checkbox-group input[type="checkbox"] {
            width: auto;
            margin-top: 0.25rem;
            accent-color: #00ff41;
        }

        .checkbox-group label {
            font-size: 0.875rem;
            color: #00ff41;
            line-height: 1.5;
            opacity: 0.9;
        }

        .btn {
            width: 100%;
            padding: 1rem 2rem;
            background: rgba(0, 255, 65, 0.1);
            color: #00ff41;
            border: 2px solid #00ff41;
            border-radius: 5px;
            font-size: 1.125rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Ubuntu', monospace;
            text-shadow: 0 0 5px #00ff41;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn:hover {
            background: rgba(0, 255, 65, 0.2);
            box-shadow: 0 0 30px rgba(0, 255, 65, 0.5);
            transform: scale(1.02);
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .error-message {
            color: #ff0000;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            text-shadow: 0 0 5px #ff0000;
        }

        .back-link {
            display: inline-block;
            margin-top: 1rem;
            color: #00ff41;
            text-decoration: none;
            font-size: 0.875rem;
            opacity: 0.8;
            transition: all 0.3s;
        }

        .back-link:hover {
            opacity: 1;
            text-shadow: 0 0 10px #00ff41;
        }
    </style>
</head>
<body>
    <canvas id="c"></canvas>
    <div id="matrix-background"></div>

    <div class="content-wrapper">
        <div class="container">
            <div class="order-card">
                <div class="order-header">
                    <h1>ОФОРМЛЕНИЕ ЗАКАЗА</h1>
                    <div class="ticket-type">{{ $ticket->type === 'vip' ? 'VIP' : 'ОБЫЧНЫЙ' }}</div>
                </div>

                <div class="order-content">
                    <div class="ticket-summary">
                        <div class="ticket-summary-row">
                            <span>Билет:</span>
                            <span><strong>{{ $ticket->name }}</strong></span>
                        </div>
                        <div class="ticket-summary-row">
                            <span>Цена за единицу:</span>
                            <span>{{ number_format($ticket->price, 2, '.', ' ') }} ₽</span>
                        </div>
                        <div class="ticket-summary-row">
                            <span>Доступно:</span>
                            <span>{{ $ticket->available_quantity }} шт.</span>
                        </div>
                        <div class="ticket-summary-row" id="total-row">
                            <span>Итого:</span>
                            <span id="total-amount">{{ number_format($ticket->price, 2, '.', ' ') }} ₽</span>
                        </div>
                    </div>

                    <form action="{{ route('orders.create') }}" method="POST" id="order-form">
                        @csrf
                        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

                        <div class="form-row">
                            <div class="form-group">
                                <label for="first_name">Имя *</label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                @error('first_name')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="last_name">Фамилия *</label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone">Телефон *</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+7 (999) 123-45-67" required>
                                @error('phone')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">Email *</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="activity_type">Вид деятельности *</label>
                            <select id="activity_type" name="activity_type" required>
                                <option value="">Выберите вид деятельности</option>
                                <option value="podologist" {{ old('activity_type') === 'podologist' ? 'selected' : '' }}>Подолог</option>
                                <option value="aesthetician" {{ old('activity_type') === 'aesthetician' ? 'selected' : '' }}>Эстетист</option>
                                <option value="manager" {{ old('activity_type') === 'manager' ? 'selected' : '' }}>Руководитель</option>
                            </select>
                            @error('activity_type')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="quantity">Количество билетов *</label>
                            <input type="number" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" min="1" max="{{ $ticket->available_quantity }}" required>
                            @error('quantity')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="promo-code-section">
                            <label for="promo_code" style="margin-bottom: 0.75rem; display: block; color: #00ff41; text-shadow: 0 0 5px #00ff41;">Промокод (необязательно)</label>
                            <div class="promo-code-input-group">
                                <input type="text" id="promo_code" name="promo_code" value="{{ old('promo_code') }}" placeholder="Введите промокод">
                                <button type="button" id="check-promo-btn" class="btn" style="width: auto; padding: 0.875rem 1.5rem; font-size: 0.875rem;">Проверить</button>
                            </div>
                            <div id="promo-result" class="promo-code-result"></div>
                        </div>

                        <div class="form-group">
                            <label>Способ оплаты *</label>
                            <div class="payment-method-group">
                                <div class="payment-method-option">
                                    <input type="radio" id="payment_qr" name="payment_method" value="qr" {{ old('payment_method', 'qr') === 'qr' ? 'checked' : '' }} required>
                                    <label for="payment_qr">QR код</label>
                                </div>
                                <div class="payment-method-option">
                                    <input type="radio" id="payment_sbp" name="payment_method" value="sbp" {{ old('payment_method') === 'sbp' ? 'checked' : '' }} required>
                                    <label for="payment_sbp">СБП</label>
                                </div>
                            </div>
                            @error('payment_method')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="checkbox-group">
                            <input type="checkbox" id="personal_data_agreement" name="personal_data_agreement" value="1" {{ old('personal_data_agreement') ? 'checked' : '' }} required>
                            <label for="personal_data_agreement">
                                Я согласен(а) на обработку персональных данных *
                            </label>
                        </div>
                        @error('personal_data_agreement')
                            <div class="error-message">{{ $message }}</div>
                        @enderror

                        @if($errors->any())
                            <div class="error-message" style="margin-bottom: 1rem; padding: 1rem; border: 1px solid #ff0000; border-radius: 5px; background: rgba(255, 0, 0, 0.1);">
                                <strong>Ошибки при заполнении формы:</strong>
                                <ul style="margin-top: 0.5rem; margin-left: 1.5rem;">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <button type="submit" class="btn">ОФОРМИТЬ ЗАКАЗ</button>
                    </form>

                    <a href="{{ route('index') }}" class="back-link">← Вернуться на главную</a>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/backgraund.js') }}"></script>
    <script src="{{ asset('assets/js/matrix.js') }}"></script>
    <script>
        const ticketPrice = {{ $ticket->price }};
        const quantityInput = document.getElementById('quantity');
        const totalAmount = document.getElementById('total-amount');
        const promoCodeInput = document.getElementById('promo_code');
        const checkPromoBtn = document.getElementById('check-promo-btn');
        const promoResult = document.getElementById('promo-result');
        let currentDiscount = 0;

        function updateTotal() {
            const quantity = parseInt(quantityInput.value) || 1;
            const subtotal = ticketPrice * quantity;
            const total = subtotal - currentDiscount;
            totalAmount.textContent = number_format(total, 2, '.', ' ') + ' ₽';
        }

        quantityInput.addEventListener('input', updateTotal);

        checkPromoBtn.addEventListener('click', async function() {
            const code = promoCodeInput.value.trim();
            if (!code) {
                promoResult.className = 'promo-code-result error';
                promoResult.textContent = 'Введите промокод';
                return;
            }

            checkPromoBtn.disabled = true;
            checkPromoBtn.textContent = 'Проверка...';

            try {
                const response = await fetch(`/api/promo-code/check?code=${encodeURIComponent(code)}&amount=${ticketPrice * (parseInt(quantityInput.value) || 1)}`);
                const data = await response.json();

                if (data.valid) {
                    promoResult.className = 'promo-code-result success';
                    promoResult.textContent = `Промокод применен! Скидка: ${data.discount_text}`;
                    currentDiscount = data.discount_amount || 0;
                    updateTotal();
                } else {
                    promoResult.className = 'promo-code-result error';
                    promoResult.textContent = data.message || 'Промокод недействителен';
                    currentDiscount = 0;
                    updateTotal();
                }
            } catch (error) {
                promoResult.className = 'promo-code-result error';
                promoResult.textContent = 'Ошибка при проверке промокода';
                currentDiscount = 0;
                updateTotal();
            } finally {
                checkPromoBtn.disabled = false;
                checkPromoBtn.textContent = 'Проверить';
            }
        });

        function number_format(number, decimals, dec_point, thousands_sep) {
            number = (number + '').replace(',', '').replace(' ', '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }
    </script>
</body>
</html>
