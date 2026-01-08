<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заказы - Админ панель</title>
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
            <h1>Заказы</h1>
        </div>

        <div class="filters">
            <form method="GET" action="{{ route('admin.orders.index') }}">
                <input type="text" name="search" placeholder="Поиск..." value="{{ request('search') }}">
                <select name="status">
                    <option value="">Все статусы</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Ожидает оплаты</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Оплачен</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Ошибка</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Отменен</option>
                </select>
                <button type="submit">Фильтровать</button>
            </form>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Номер заказа</th>
                        <th>Покупатель</th>
                        <th>Билет</th>
                        <th>Количество</th>
                        <th>Сумма</th>
                        <th>Статус</th>
                        <th>Чек</th>
                        <th>Билет</th>
                        <th>Дата</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td><strong>{{ $order->order_number }}</strong></td>
                            <td>{{ $order->customer->full_name }}<br><small>{{ $order->customer->email }}</small></td>
                            <td>{{ $order->ticket->name }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td><strong>{{ $order->formatted_total_amount }}</strong></td>
                            <td>
                                <span class="badge badge-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ $order->payment_status_label }}
                                </span>
                            </td>
                            <td>
                                @if($order->payment_status === 'paid')
                                    @if($order->receipt_sent)
                                        <span class="badge badge-success">✓ Отправлен</span>
                                    @else
                                        <span class="badge badge-warning">Не отправлен</span>
                                    @endif
                                @else
                                    <span class="badge badge-gray">—</span>
                                @endif
                            </td>
                            <td>
                                @if($order->payment_status === 'paid')
                                    @if($order->ticket_sent)
                                        <span class="badge badge-success">✓ Отправлен</span>
                                    @else
                                        <span class="badge badge-warning">Не отправлен</span>
                                    @endif
                                @else
                                    <span class="badge badge-gray">—</span>
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-primary">Просмотр</a>
                                @if($order->payment_status === 'pending')
                                    <form action="{{ route('admin.orders.verify-payment', $order->id) }}" method="POST" style="display: inline-block; margin-top: 0.5rem;">
                                        @csrf
                                        <button type="submit" class="btn btn-success" onclick="return confirm('Подтвердить оплату заказа №{{ $order->order_number }}? Чек и билет будут отправлены на email покупателя.')">Подтвердить оплату</button>
                                    </form>
                                    @if(abs($order->total_amount - 10.00) < 0.01)
                                    <form action="{{ route('admin.orders.test-check', $order->id) }}" method="POST" style="display: inline-block; margin-top: 0.5rem;">
                                        @csrf
                                        <button type="submit" class="btn" style="background: #8b5cf6; color: white; font-size: 0.875rem;" onclick="return confirm('Тестовая проверка для заказа на 10₽?')">🧪 Тест</button>
                                    </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" style="text-align: center; padding: 2rem;">Заказы не найдены</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $orders->links() }}
        </div>
    </div>
</body>
</html>

