<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Ticket;
use App\Models\PromoCode;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function dashboard()
    {
        $totalOrders = Order::count();
        $paidOrders = Order::where('payment_status', 'paid')->count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $pendingOrders = Order::where('payment_status', 'pending')->count();

        return view('admin.dashboard', compact('totalOrders', 'paidOrders', 'totalRevenue', 'pendingOrders'));
    }

    public function orders(Request $request)
    {
        $query = Order::with(['customer', 'ticket', 'promoCode']);

        // Фильтрация
        if ($request->has('status') && $request->status) {
            $query->where('payment_status', $request->status);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            })->orWhere('order_number', 'like', "%{$search}%");
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function orderShow($id)
    {
        $order = Order::with(['customer', 'ticket', 'promoCode'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function customers(Request $request)
    {
        $query = Customer::withCount('orders');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->has('activity_type') && $request->activity_type) {
            $query->where('activity_type', $request->activity_type);
        }

        $customers = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.customers.index', compact('customers'));
    }

    public function tickets()
    {
        $tickets = Ticket::withCount('orders')->orderBy('created_at', 'desc')->get();
        return view('admin.tickets.index', compact('tickets'));
    }

    public function promoCodes()
    {
        $promoCodes = PromoCode::withCount('orders')->orderBy('created_at', 'desc')->get();
        return view('admin.promo_codes.index', compact('promoCodes'));
    }

    public function verifyPayment($id)
    {
        $order = Order::with(['customer', 'ticket'])->findOrFail($id);

        if ($order->payment_status === 'paid') {
            return back()->with('error', 'Заказ уже оплачен');
        }

        // Обновление статуса оплаты
        $order->update(['payment_status' => 'paid']);

        // Генерация и отправка чека
        $paymentController = new \App\Http\Controllers\PaymentController();
        $paymentController->generateAndSendReceipt($order);

        return back()->with('success', 'Оплата подтверждена. Чек и билет отправлены на email покупателя.');
    }

    public function testCheckPayment($id)
    {
        $order = Order::with(['customer', 'ticket'])->findOrFail($id);

        if ($order->payment_status === 'paid') {
            return back()->with('error', 'Заказ уже оплачен');
        }

        // Проверка суммы для тестовой проверки
        if (abs($order->total_amount - 10.00) > 0.01) {
            return back()->with('error', 'Тестовая проверка работает только для заказов на сумму 10 рублей. Текущая сумма: ' . $order->formatted_total_amount);
        }

        // Обновление статуса оплаты
        $order->update(['payment_status' => 'paid']);

        // Генерация и отправка чека
        $paymentController = new \App\Http\Controllers\PaymentController();
        $paymentController->generateAndSendReceipt($order);

        return back()->with('success', 'Тестовая проверка оплаты выполнена. Чек и билет отправлены на email покупателя: ' . $order->customer->email);
    }
}
