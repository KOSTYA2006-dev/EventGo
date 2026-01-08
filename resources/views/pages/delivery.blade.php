<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Доставка и получение - EventGo</title>
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
            line-height: 1.8;
        }

        .content-card h2 {
            font-size: 1.5rem;
            margin: 2rem 0 1rem 0;
            color: #00ff41;
            text-shadow: 0 0 5px #00ff41;
        }

        .content-card h2:first-child {
            margin-top: 0;
        }

        .content-card p {
            margin-bottom: 1rem;
            color: rgba(0, 255, 65, 0.9);
        }

        .content-card ul {
            margin-left: 2rem;
            margin-bottom: 1rem;
        }

        .content-card li {
            margin-bottom: 0.5rem;
            color: rgba(0, 255, 65, 0.9);
        }

        .highlight-box {
            background: rgba(0, 255, 65, 0.1);
            border: 1px solid #00ff41;
            border-radius: 5px;
            padding: 1.5rem;
            margin: 1.5rem 0;
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
            <h1 class="page-title">ДОСТАВКА И ПОЛУЧЕНИЕ ЗАКАЗА</h1>

            <div class="content-card">
                <div class="highlight-box">
                    <p style="font-weight: bold; font-size: 1.1rem; margin-bottom: 0.5rem;">
                        📧 Электронная доставка билетов
                    </p>
                    <p>
                        Все билеты доставляются в электронном виде на указанный при оформлении заказа email адрес 
                        сразу после успешной оплаты.
                    </p>
                </div>

                <h2>Способ получения билета</h2>
                <p>
                    После успешной оплаты заказа вы получите на указанный email адрес:
                </p>
                <ul>
                    <li><strong>Чек об оплате</strong> - подтверждение оплаты заказа</li>
                    <li><strong>Электронный билет</strong> - ваш билет на мероприятие в формате HTML</li>
                </ul>

                <h2>Сроки доставки</h2>
                <p>
                    Билеты отправляются автоматически в течение нескольких минут после успешной оплаты. 
                    Если вы не получили билет в течение 30 минут, проверьте папку "Спам" или свяжитесь 
                    с нашей службой поддержки.
                </p>

                <h2>Как использовать билет</h2>
                <ul>
                    <li>Сохраните полученный билет на ваше устройство (телефон, планшет)</li>
                    <li>Распечатайте билет или покажите его на экране устройства при входе на мероприятие</li>
                    <li>Билет содержит уникальный номер заказа, который будет проверен при входе</li>
                    <li>Убедитесь, что на билете указаны ваши данные (ФИО, email)</li>
                </ul>

                <h2>Важная информация</h2>
                <div class="highlight-box">
                    <ul style="margin-left: 0;">
                        <li>Билет является именным и не подлежит передаче третьим лицам</li>
                        <li>При входе на мероприятие необходимо предъявить документ, удостоверяющий личность</li>
                        <li>Билет действителен только на указанную дату и время мероприятия</li>
                        <li>В случае потери билета свяжитесь с нами для восстановления</li>
                    </ul>
                </div>

                <h2>Контроль качества</h2>
                <p>
                    Если вы столкнулись с проблемами при получении билета или у вас есть вопросы, 
                    пожалуйста, свяжитесь с нашей службой поддержки:
                </p>
                <ul>
                    <li>Email: {{ config('app.contact_email', 'support@eventgo.ru') }}</li>
                    <li>Телефон: {{ config('app.contact_phone', '+7 (999) 123-45-67') }}</li>
                </ul>
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

