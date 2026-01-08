<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Пользовательское соглашение - EventGo</title>
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
            <h1 class="page-title">ПОЛЬЗОВАТЕЛЬСКОЕ СОГЛАШЕНИЕ</h1>

            <div class="content-card">
                <p style="text-align: center; margin-bottom: 2rem; font-weight: bold;">
                    ПУБЛИЧНАЯ ОФЕРТА<br>
                    на оказание услуг по продаже билетов на мероприятия
                </p>

                <h2>1. ОБЩИЕ ПОЛОЖЕНИЯ</h2>
                <p>
                    Настоящая публичная оферта (далее – «Оферта») является официальным предложением 
                    {{ config('payment.recipient_name', 'ИП ЛАЗАРЕВА СВЕТЛАНА ИГОРЕВНА') }} 
                    (далее – «Исполнитель») заключить договор оказания услуг на условиях, изложенных ниже.
                </p>
                <p>
                    В соответствии с пунктом 2 статьи 437 Гражданского кодекса Российской Федерации 
                    данный документ является публичной офертой. Акцепт настоящей оферты означает 
                    полное и безоговорочное принятие Покупателем всех условий без каких-либо изъятий 
                    и ограничений.
                </p>

                <h2>2. ПРЕДМЕТ ДОГОВОРА</h2>
                <p>
                    Исполнитель обязуется оказать Покупателю услуги по продаже билетов на мероприятия, 
                    а Покупатель обязуется оплатить указанные услуги в порядке и на условиях, 
                    предусмотренных настоящей Офертой.
                </p>

                <h2>3. ПОРЯДОК ОКАЗАНИЯ УСЛУГ</h2>
                <ul>
                    <li>Покупатель выбирает билет на сайте и оформляет заказ</li>
                    <li>Покупатель заполняет форму с контактными данными</li>
                    <li>Покупатель производит оплату выбранного билета</li>
                    <li>После оплаты билет отправляется на указанный email адрес</li>
                    <li>Билет является электронным документом, подтверждающим право на посещение мероприятия</li>
                </ul>

                <h2>4. СТОИМОСТЬ УСЛУГ И ПОРЯДОК ОПЛАТЫ</h2>
                <p>
                    Стоимость услуг указана на сайте и может быть изменена Исполнителем в одностороннем порядке. 
                    Оплата производится путем перечисления денежных средств через платежную систему YooKassa 
                    или иными способами, указанными на сайте.
                </p>

                <h2>5. ПРАВА И ОБЯЗАННОСТИ СТОРОН</h2>
                <p><strong>Исполнитель обязуется:</strong></p>
                <ul>
                    <li>Оказать услуги в соответствии с условиями настоящей Оферты</li>
                    <li>Предоставить билет после получения оплаты</li>
                    <li>Обеспечить конфиденциальность персональных данных Покупателя</li>
                </ul>
                <p><strong>Покупатель обязуется:</strong></p>
                <ul>
                    <li>Предоставить достоверную информацию при оформлении заказа</li>
                    <li>Своевременно оплатить выбранные услуги</li>
                    <li>Сохранить билет до момента посещения мероприятия</li>
                </ul>

                <h2>6. ВОЗВРАТ СРЕДСТВ</h2>
                <p>
                    Возврат денежных средств за билеты осуществляется в соответствии с законодательством 
                    Российской Федерации. Возврат возможен не позднее чем за 3 дня до начала мероприятия.
                </p>

                <h2>7. ОТВЕТСТВЕННОСТЬ СТОРОН</h2>
                <p>
                    Исполнитель не несет ответственности за отмену или перенос мероприятия организатором. 
                    В случае отмены мероприятия возврат денежных средств осуществляется в полном объеме.
                </p>

                <h2>8. ЗАКЛЮЧИТЕЛЬНЫЕ ПОЛОЖЕНИЯ</h2>
                <p>
                    Настоящая Оферта вступает в силу с момента размещения на сайте и действует до момента 
                    отзыва Оферты Исполнителем.
                </p>
                <p>
                    Все споры решаются путем переговоров, а при недостижении соглашения – в соответствии 
                    с законодательством Российской Федерации.
                </p>
                <p style="margin-top: 2rem;">
                    <strong>Реквизиты Исполнителя:</strong><br>
                    {{ config('payment.recipient_name', 'ИП ЛАЗАРЕВА СВЕТЛАНА ИГОРЕВНА') }}<br>
                    ИНН: {{ config('payment.vtb_inn', '616404172802') }}<br>
                    ОГРНИП: {{ config('payment.ogrnip', '316616400101234') }}
                </p>
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

