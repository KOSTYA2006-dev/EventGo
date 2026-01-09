<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>EventGo - Купить билеты</title>
    <link rel="stylesheet" href="{{ asset('assets/css/main-set.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main-page.css') }}">
    <style>
        @font-face {
            font-family: 'Ubuntu';
            src: url('{{ asset("assets/text-style/Ubuntu/Ubuntu-Regular.ttf") }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Ubuntu';
            src: url('{{ asset("assets/text-style/Ubuntu/Ubuntu-Bold.ttf") }}') format('truetype');
            font-weight: bold;
            font-style: normal;
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
            overflow-x: hidden;
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
            to {
                transform: translateY(100vh);
            }
        }

        .content-wrapper {
            position: relative;
            z-index: 10;
            min-height: 100vh;
        }

        header {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 2px solid #00ff41;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 0 20px rgba(0, 255, 65, 0.3);
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 0;
        }

        .logo {
            font-size: 2rem;
            font-weight: bold;
            color: #00ff41;
            text-shadow: 0 0 10px #00ff41, 0 0 20px #00ff41;
            letter-spacing: 3px;
        }

        .admin-link {
            color: #00ff41;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border: 1px solid #00ff41;
            border-radius: 5px;
            transition: all 0.3s;
            text-shadow: 0 0 5px #00ff41;
        }

        .admin-link:hover {
            background: rgba(0, 255, 65, 0.1);
            box-shadow: 0 0 15px rgba(0, 255, 65, 0.5);
        }

        .hero {
            text-align: center;
            padding: 4rem 0;
            color: #00ff41;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
            text-shadow: 0 0 10px #00ff41, 0 0 20px #00ff41, 0 0 30px #00ff41;
            letter-spacing: 2px;
            animation: glow 2s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from {
                text-shadow: 0 0 10px #00ff41, 0 0 20px #00ff41, 0 0 30px #00ff41;
            }
            to {
                text-shadow: 0 0 20px #00ff41, 0 0 30px #00ff41, 0 0 40px #00ff41, 0 0 50px #00ff41;
            }
        }

        .hero p {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            text-shadow: 0 0 5px #00ff41;
            opacity: 0.9;
        }

        .countdown {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin: 3rem 0;
            flex-wrap: wrap;
        }

        .countdown-item {
            background: rgba(0, 255, 65, 0.1);
            border: 2px solid #00ff41;
            border-radius: 10px;
            padding: 2rem;
            min-width: 120px;
            text-align: center;
            box-shadow: 0 0 20px rgba(0, 255, 65, 0.3);
            backdrop-filter: blur(5px);
        }

        .countdown-number {
            font-size: 3rem;
            font-weight: bold;
            display: block;
            color: #00ff41;
            text-shadow: 0 0 10px #00ff41;
        }

        .countdown-label {
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 0.5rem;
            opacity: 0.8;
        }

        .tickets-section {
            background: rgba(0, 0, 0, 0.8);
            border: 2px solid #00ff41;
            border-radius: 15px;
            padding: 3rem 2rem;
            margin: 3rem 0;
            box-shadow: 0 0 30px rgba(0, 255, 65, 0.3);
            backdrop-filter: blur(10px);
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 2rem;
            color: #00ff41;
            text-shadow: 0 0 10px #00ff41;
            letter-spacing: 2px;
        }

        .tickets-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .ticket-card {
            background: rgba(0, 0, 0, 0.9);
            border: 2px solid #00ff41;
            border-radius: 10px;
            padding: 2rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .ticket-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 255, 65, 0.1), transparent);
            transition: left 0.5s;
        }

        .ticket-card:hover::before {
            left: 100%;
        }

        .ticket-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 30px rgba(0, 255, 65, 0.5);
            border-color: #00ff41;
        }

        .ticket-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            font-size: 0.875rem;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 1rem;
            border: 1px solid #00ff41;
            background: rgba(0, 255, 65, 0.1);
            color: #00ff41;
            text-shadow: 0 0 5px #00ff41;
        }

        .ticket-badge.vip {
            background: rgba(0, 255, 65, 0.2);
            box-shadow: 0 0 15px rgba(0, 255, 65, 0.5);
        }

        .ticket-name {
            font-size: 1.75rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #00ff41;
            text-shadow: 0 0 5px #00ff41;
        }

        .ticket-description {
            color: #00ff41;
            margin-bottom: 1.5rem;
            opacity: 0.8;
            min-height: 60px;
        }

        .ticket-price {
            font-size: 2.5rem;
            font-weight: bold;
            color: #00ff41;
            margin-bottom: 1.5rem;
            text-shadow: 0 0 10px #00ff41;
        }

        .btn {
            display: inline-block;
            padding: 1rem 2rem;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid #00ff41;
            cursor: pointer;
            font-size: 1rem;
            width: 100%;
            background: rgba(0, 255, 65, 0.1);
            color: #00ff41;
            text-shadow: 0 0 5px #00ff41;
            font-family: 'Ubuntu', monospace;
        }

        .btn:hover {
            background: rgba(0, 255, 65, 0.2);
            box-shadow: 0 0 20px rgba(0, 255, 65, 0.5);
            transform: scale(1.05);
        }

        footer {
            background: rgba(0, 0, 0, 0.8);
            border-top: 2px solid #00ff41;
            color: #00ff41;
            text-align: center;
            padding: 2rem 0;
            margin-top: 3rem;
            text-shadow: 0 0 5px #00ff41;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .countdown {
                gap: 1rem;
            }

            .countdown-item {
                min-width: 80px;
                padding: 1rem;
            }

            .countdown-number {
                font-size: 2rem;
            }

            .tickets-grid {
                grid-template-columns: 1fr;
            }

            .tickets-section {
                padding: 2rem 1rem;
            }

            .container {
                padding: 0 20px;
            }
        }
    </style>
</head>
<body>
    <canvas id="c"></canvas>
    <div id="matrix-background"></div>

    <div class="content-wrapper">
        <header>
            <nav class="container">
                <div class="logo">EVENTGO</div>
                <div>
                    <a href="{{ route('admin.login') }}" class="admin-link">ADMIN</a>
                </div>
            </nav>
        </header>

        <main>
            <div class="hero">
                <div class="container">
                    <h1>ДОБРО ПОЖАЛОВАТЬ</h1>
                    <p>Присоединяйтесь к нам для незабываемого опыта</p>
                    
                    <div class="countdown" id="countdown">
                        <div class="countdown-item">
                            <span class="countdown-number" id="days">00</span>
                            <span class="countdown-label">Дней</span>
                        </div>
                        <div class="countdown-item">
                            <span class="countdown-number" id="hours">00</span>
                            <span class="countdown-label">Часов</span>
                        </div>
                        <div class="countdown-item">
                            <span class="countdown-number" id="minutes">00</span>
                            <span class="countdown-label">Минут</span>
                        </div>
                        <div class="countdown-item">
                            <span class="countdown-number" id="seconds">00</span>
                            <span class="countdown-label">Секунд</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="tickets-section">
                    <h2 class="section-title">ВЫБЕРИТЕ БИЛЕТ</h2>
                    
                    @if($tickets->count() > 0)
                        <div class="tickets-grid">
                            @foreach($tickets as $ticket)
                                <div class="ticket-card">
                                    <span class="ticket-badge {{ $ticket->type }}">{{ $ticket->type === 'vip' ? 'VIP' : 'ОБЫЧНЫЙ' }}</span>
                                    <h3 class="ticket-name">{{ $ticket->name }}</h3>
                                    <p class="ticket-description">{{ $ticket->description ?? 'Отличный билет для участия в мероприятии' }}</p>
                                    <div class="ticket-price">{{ $ticket->formatted_price }}</div>
                                    <a href="{{ route('orders.show', $ticket->id) }}" class="btn">КУПИТЬ БИЛЕТ</a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p style="text-align: center; color: #00ff41; padding: 2rem; opacity: 0.7;">Билеты пока не доступны</p>
                    @endif
                </div>
            </div>
        </main>

        <footer>
            <div class="container">
                <div style="display: flex; justify-content: center; gap: 2rem; flex-wrap: wrap; margin-bottom: 1rem;">
                    <a href="{{ route('pages.requisites') }}" style="color: #00ff41; text-decoration: none; transition: all 0.3s;">Реквизиты</a>
                    <a href="{{ route('pages.agreement') }}" style="color: #00ff41; text-decoration: none; transition: all 0.3s;">Пользовательское соглашение</a>
                    <a href="{{ route('pages.delivery') }}" style="color: #00ff41; text-decoration: none; transition: all 0.3s;">Доставка и получение</a>
                    <a href="{{ route('pages.contacts') }}" style="color: #00ff41; text-decoration: none; transition: all 0.3s;">Контакты</a>
                </div>
                <p>&copy; {{ date('Y') }} EventGo. Все права защищены.</p>
                <p style="margin-top: 0.5rem; font-size: 0.875rem; opacity: 0.8;">
                    ИНН: {{ config('payment.vtb_inn', '616404172802') }} | 
                    ОГРНИП: {{ config('payment.ogrnip', '316616400101234') }}
                </p>
            </div>
        </footer>
    </div>

    <!-- <script src="{{ asset('assets/js/backgraund.js'}}"></script>
    <script src="{{ asset('assets/js/matrix.js'}}"></script> -->
    <script src="{{ secure_asset('assets/js/backgraund.js') }}"></script>
<script src="{{ secure_asset('assets/js/matrix.js') }}"></script>
    <script>
        // Countdown Timer
        const eventDate = new Date('{{ $eventDate }}').getTime();
        
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = eventDate - now;

            if (distance < 0) {
                document.getElementById('countdown').innerHTML = '<div style="text-align: center; padding: 2rem; font-size: 1.5rem; font-weight: bold; color: #00ff41; text-shadow: 0 0 10px #00ff41;">МЕРОПРИЯТИЕ НАЧАЛОСЬ!</div>';
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById('days').textContent = String(days).padStart(2, '0');
            document.getElementById('hours').textContent = String(hours).padStart(2, '0');
            document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
            document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
        }

        setInterval(updateCountdown, 1000);
        updateCountdown();
    </script>
</body>
</html>
