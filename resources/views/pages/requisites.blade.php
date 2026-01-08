<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Реквизиты - EventGo</title>
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

        .content-wrapper {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 2px solid #00ff41;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 0;
            max-width: 1200px;
            margin: 0 auto;
            padding-left: 20px;
            padding-right: 20px;
        }

        .logo {
            font-size: 2rem;
            font-weight: bold;
            color: #00ff41;
            text-shadow: 0 0 10px #00ff41;
            letter-spacing: 3px;
            text-decoration: none;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem 20px;
            flex: 1;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 2rem;
            color: #00ff41;
            text-shadow: 0 0 10px #00ff41;
        }

        .content-card {
            background: rgba(0, 0, 0, 0.9);
            border: 2px solid #00ff41;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 0 30px rgba(0, 255, 65, 0.3);
        }

        .info-section {
            margin-bottom: 2rem;
        }

        .info-section h2 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #00ff41;
            text-shadow: 0 0 5px #00ff41;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(0, 255, 65, 0.3);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: bold;
            color: #00ff41;
        }

        .info-value {
            color: rgba(0, 255, 65, 0.9);
        }

        footer {
            background: rgba(0, 0, 0, 0.8);
            border-top: 2px solid #00ff41;
            color: #00ff41;
            padding: 2rem 0;
            margin-top: auto;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            text-align: center;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .footer-links a {
            color: #00ff41;
            text-decoration: none;
            transition: all 0.3s;
        }

        .footer-links a:hover {
            text-shadow: 0 0 10px #00ff41;
        }

        .btn-back {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: rgba(0, 255, 65, 0.1);
            border: 2px solid #00ff41;
            border-radius: 5px;
            color: #00ff41;
            text-decoration: none;
            margin-top: 2rem;
            transition: all 0.3s;
        }

        .btn-back:hover {
            background: rgba(0, 255, 65, 0.2);
            box-shadow: 0 0 20px rgba(0, 255, 65, 0.5);
        }
    </style>
</head>
<body>
    <canvas id="c"></canvas>
    <div id="matrix-background"></div>

    <div class="content-wrapper">
        <header>
            <nav>
                <a href="{{ route('index') }}" class="logo">EVENTGO</a>
                <a href="{{ route('index') }}" style="color: #00ff41; text-decoration: none;">ГЛАВНАЯ</a>
            </nav>
        </header>

        <div class="container">
            <h1 class="page-title">РЕКВИЗИТЫ</h1>

            <div class="content-card">
                <div class="info-section">
                    <h2>Индивидуальный предприниматель</h2>
                    <div class="info-row">
                        <span class="info-label">Полное наименование:</span>
                        <span class="info-value">{{ config('payment.recipient_name', 'ИП ЛАЗАРЕВА СВЕТЛАНА ИГОРЕВНА') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">ИНН:</span>
                        <span class="info-value">{{ config('payment.vtb_inn', '616404172802') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">ОГРНИП:</span>
                        <span class="info-value">{{ config('payment.ogrnip', '316616400101234') }}</span>
                    </div>
                </div>

                <div class="info-section">
                    <h2>Банковские реквизиты</h2>
                    <div class="info-row">
                        <span class="info-label">Расчетный счет:</span>
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
                        <span class="info-label">Корреспондентский счет:</span>
                        <span class="info-value">{{ config('payment.vtb_correspondent_account', '30101810145250000411') }}</span>
                    </div>
                </div>

                <div class="info-section">
                    <h2>Контактная информация</h2>
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ config('app.contact_email', 'info@eventgo.ru') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Телефон:</span>
                        <span class="info-value">{{ config('app.contact_phone', '+7 (999) 123-45-67') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Сайт:</span>
                        <span class="info-value">{{ config('app.url', 'https://eventgo.ru') }}</span>
                    </div>
                </div>
            </div>

            <a href="{{ route('index') }}" class="btn-back">← Вернуться на главную</a>
        </div>

        <footer>
            <div class="footer-content">
                <div class="footer-links">
                    <a href="{{ route('pages.requisites') }}">Реквизиты</a>
                    <a href="{{ route('pages.agreement') }}">Пользовательское соглашение</a>
                    <a href="{{ route('pages.delivery') }}">Доставка и получение</a>
                    <a href="{{ route('pages.contacts') }}">Контакты</a>
                </div>
                <p>&copy; {{ date('Y') }} EventGo. Все права защищены.</p>
                <p style="margin-top: 0.5rem; font-size: 0.875rem; opacity: 0.8;">
                    ИНН: {{ config('payment.vtb_inn', '616404172802') }} | 
                    ОГРНИП: {{ config('payment.ogrnip', '316616400101234') }}
                </p>
            </div>
        </footer>
    </div>

    <script src="{{ asset('assets/js/backgraund.js') }}"></script>
    <script src="{{ asset('assets/js/matrix.js') }}"></script>
</body>
</html>

