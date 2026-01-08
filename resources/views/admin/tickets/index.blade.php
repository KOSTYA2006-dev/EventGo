<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Билеты - Админ панель</title>
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
                <a href="{{ route('admin.orders.index') }}" class="nav-link">Заказы</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.customers.index') }}" class="nav-link">Покупатели</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.tickets.index') }}" class="nav-link active">Билеты</a>
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
        <div class="header">
            <h1>Билеты</h1>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Название</th>
                        <th>Тип</th>
                        <th>Цена</th>
                        <th>Доступно</th>
                        <th>Продано</th>
                        <th>Статус</th>
                        <th>Дата создания</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr>
                            <td><strong>{{ $ticket->name }}</strong></td>
                            <td>
                                <span class="badge badge-{{ $ticket->type === 'vip' ? 'vip' : 'regular' }}">
                                    {{ $ticket->type === 'vip' ? 'VIP' : 'Обычный' }}
                                </span>
                            </td>
                            <td><strong>{{ $ticket->formatted_price }}</strong></td>
                            <td>{{ $ticket->available_quantity }}</td>
                            <td><strong>{{ $ticket->orders_count }}</strong></td>
                            <td>
                                <span class="badge badge-{{ $ticket->is_active ? 'active' : 'inactive' }}">
                                    {{ $ticket->is_active ? 'Активен' : 'Неактивен' }}
                                </span>
                            </td>
                            <td>{{ $ticket->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 2rem;">Билеты не найдены</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

