<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ панель - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #6366f1;
            --secondary: #8b5cf6;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1f2937;
            --light: #f9fafb;
            --white: #ffffff;
            --gray: #6b7280;
            --gray-light: #e5e7eb;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--light);
            color: var(--dark);
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 250px;
            background: var(--white);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            padding: 2rem 0;
        }

        .sidebar-header {
            padding: 0 1.5rem;
            margin-bottom: 2rem;
        }

        .sidebar-header h2 {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-item {
            margin: 0.5rem 0;
        }

        .nav-link {
            display: block;
            padding: 1rem 1.5rem;
            color: var(--gray);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .nav-link:hover,
        .nav-link.active {
            background: var(--light);
            color: var(--primary);
            border-left-color: var(--primary);
        }

        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }

        .header {
            background: var(--white);
            padding: 1.5rem 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--white);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-icon.primary { background: rgba(99, 102, 241, 0.1); color: var(--primary); }
        .stat-icon.success { background: rgba(16, 185, 129, 0.1); color: var(--success); }
        .stat-icon.warning { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
        .stat-icon.danger { background: rgba(239, 68, 68, 0.1); color: var(--danger); }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--gray);
            font-size: 0.875rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-block;
        }

        .btn-danger {
            background: var(--danger);
            color: var(--white);
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .main-content {
                margin-left: 0;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>EventGo Admin</h2>
        </div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link active">Dashboard</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.orders.index') }}" class="nav-link">Заказы</a>
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
        <div class="header">
            <h1>Панель управления</h1>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon primary">📊</div>
                <div class="stat-value">{{ $totalOrders }}</div>
                <div class="stat-label">Всего заказов</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon success">✅</div>
                <div class="stat-value">{{ $paidOrders }}</div>
                <div class="stat-label">Оплаченных заказов</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon warning">💰</div>
                <div class="stat-value">{{ number_format($totalRevenue, 0, '.', ' ') }} ₽</div>
                <div class="stat-label">Общая выручка</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon danger">⏳</div>
                <div class="stat-value">{{ $pendingOrders }}</div>
                <div class="stat-label">Ожидают оплаты</div>
            </div>
        </div>

        <div style="background: var(--white); padding: 2rem; border-radius: 15px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);">
            <h2 style="margin-bottom: 1rem;">Быстрые действия</h2>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="{{ route('admin.orders.index') }}" class="btn" style="background: var(--primary); color: var(--white);">Просмотр заказов</a>
                <a href="{{ route('admin.customers.index') }}" class="btn" style="background: var(--secondary); color: var(--white);">Просмотр покупателей</a>
                <a href="{{ route('admin.tickets.index') }}" class="btn" style="background: var(--success); color: var(--white);">Управление билетами</a>
            </div>
        </div>
    </div>
</body>
</html>

