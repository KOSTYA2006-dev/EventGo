<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заказ №{{ $order->order_number }} - Админ панель</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin-common.css') }}">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>EventGo Admin</h2>
        </div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.orders.index') }}" class="nav-link active">Заказы</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.customers.index') }}" class="nav-link">Покупатели</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.tickets.index') }}" class="nav-link">Билеты</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.promo_codes.index') }}" class="nav-link">Промокоды</a>
            </li>
            <li class="nav-item">
                <form action="{{ route('admin.logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="nav-link" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer; font-size: inherit; font-family: inherit;">Выход</button>
                </form>
            </li>
        </ul>
    </div>

    <div class="main-content">
        @if(session('success'))
            <div style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid #10b981;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid #ef4444;">
                {{ session('error') }}
            </div>
        @endif
        <div class="header">
            <h1>Заказ №{{ $order->order_number }}</h1>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">← Назад к заказам</a>
        </div>

        <div class="info-card">
            <h3>Информация о заказе</h3>
            <div class="info-row">
                <span class="info-label">Номер заказа:</span>
                <span class="info-value"><strong>{{ $order->order_number }}</strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">Дата создания:</span>
                <span class="info-value">{{ $order->created_at->format('d.m.Y H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Статус оплаты:</span>
                <span class="info-value">
                    <span class="badge badge-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'pending' ? 'warning' : 'danger') }}">
                        {{ $order->payment_status_label }}
                    </span>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Способ оплаты:</span>
                <span class="info-value">{{ $order->payment_method_label }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Билет:</span>
                <span class="info-value">{{ $order->ticket->name }} ({{ $order->ticket->type === 'vip' ? 'VIP' : 'Обычный' }})</span>
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
                <span class="info-value">{{ $order->promoCode->code }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Скидка:</span>
                <span class="info-value" style="color: var(--success);">-{{ number_format($order->discount_amount, 2, '.', ' ') }} ₽</span>
            </div>
            @endif
            <div class="info-row">
                <span class="info-label">Итого:</span>
                <span class="info-value"><strong style="font-size: 1.25rem; color: var(--primary);">{{ $order->formatted_total_amount }}</strong></span>
            </div>
            @if($order->payment_status === 'paid')
            <div class="info-row">
                <span class="info-label">Чек отправлен:</span>
                <span class="info-value">
                    @if($order->receipt_sent)
                        <span class="badge badge-success">✓ Да</span>
                    @else
                        <span class="badge badge-warning">Нет</span>
                    @endif
                </span>
            </div>
            @if($order->payment_receipt_path)
            <div class="info-row">
                <span class="info-label">Чек:</span>
                <span class="info-value">
                    <a href="{{ asset('storage/' . $order->payment_receipt_path) }}" target="_blank" class="btn btn-primary">Скачать чек</a>
                </span>
            </div>
            @endif
            <div class="info-row">
                <span class="info-label">Билет отправлен:</span>
                <span class="info-value">
                    @if($order->ticket_sent)
                        <span class="badge badge-success">✓ Да</span>
                    @else
                        <span class="badge badge-warning">Нет</span>
                    @endif
                </span>
            </div>
            @if($order->ticket_path)
            <div class="info-row">
                <span class="info-label">Билет:</span>
                <span class="info-value">
                    <a href="{{ asset('storage/' . $order->ticket_path) }}" target="_blank" class="btn btn-primary">Скачать билет</a>
                </span>
            </div>
            @endif
            @endif
        </div>

        <div class="info-card">
            <h3>Информация о покупателе</h3>
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

        @if($order->payment_status === 'pending')
        <div class="info-card" style="background: #fef3c7; border-left: 4px solid #f59e0b;">
            <h3 style="color: #92400e;">Действия</h3>
            <p style="margin-bottom: 1rem; color: #78350f;">После подтверждения оплаты чек и билет будут автоматически отправлены на email покупателя: <strong>{{ $order->customer->email }}</strong></p>
            
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <form action="{{ route('admin.orders.verify-payment', $order->id) }}" method="POST" style="display: inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-success" onclick="return confirm('Подтвердить оплату заказа №{{ $order->order_number }}? Чек и билет будут отправлены на email покупателя.')">
                        Подтвердить оплату и отправить чек и билет
                    </button>
                </form>
                
                @if(abs($order->total_amount - 10.00) < 0.01)
                <form action="{{ route('admin.orders.test-check', $order->id) }}" method="POST" style="display: inline-block;">
                    @csrf
                    <button type="submit" class="btn" style="background: #8b5cf6; color: white;" onclick="return confirm('Выполнить тестовую проверку оплаты для заказа на 10 рублей? Чек будет отправлен на email покупателя.')">
                        🧪 Тестовая проверка (10₽)
                    </button>
                </form>
                @else
                <button type="button" class="btn" style="background: #9ca3af; color: white; cursor: not-allowed;" disabled title="Тестовая проверка доступна только для заказов на сумму 10 рублей">
                    🧪 Тестовая проверка (10₽) - недоступно
                </button>
                @endif
            </div>
        </div>
        @endif
    </div>
</body>
</html>


