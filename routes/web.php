<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mainController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\PromoCodeController;
use App\Http\Controllers\PageController;

// Главная страница
Route::get('/', [mainController::class, 'index'])->name('index');

// API для проверки промокодов
Route::get('/api/promo-code/check', [PromoCodeController::class, 'check'])->name('api.promo-code.check');

// Информационные страницы
Route::get('/requisites', [PageController::class, 'requisites'])->name('pages.requisites');
Route::get('/agreement', [PageController::class, 'agreement'])->name('pages.agreement');
Route::get('/delivery', [PageController::class, 'delivery'])->name('pages.delivery');
Route::get('/contacts', [PageController::class, 'contacts'])->name('pages.contacts');

// Билеты
Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
Route::get('/tickets/{id}', [TicketController::class, 'show'])->name('tickets.show');

// Заказы
Route::get('/order/{id}/form', [OrderController::class, 'showForm'])->name('orders.show');
Route::post('/orders', [OrderController::class, 'create'])->name('orders.create');
Route::get('/order/{id}/details', [OrderController::class, 'show'])->name('orders.details');

// Оплата
Route::get('/payment/{id}', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/payment/{id}/confirm', [PaymentController::class, 'confirm'])->name('payment.confirm');
Route::get('/order/{id}/success', [PaymentController::class, 'success'])->name('order.success');
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');
Route::post('/payment/test-check/{id}', [PaymentController::class, 'testCheck'])->name('payment.test-check');

// Админ панель - авторизация
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    
    // Защищенные маршруты
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders.index');
        Route::get('/orders/{id}', [AdminController::class, 'orderShow'])->name('admin.orders.show');
        Route::post('/orders/{id}/verify-payment', [AdminController::class, 'verifyPayment'])->name('admin.orders.verify-payment');
        Route::post('/orders/{id}/test-check', [AdminController::class, 'testCheckPayment'])->name('admin.orders.test-check');
        Route::get('/customers', [AdminController::class, 'customers'])->name('admin.customers.index');
        Route::get('/tickets', [AdminController::class, 'tickets'])->name('admin.tickets.index');
        Route::get('/promo-codes', [AdminController::class, 'promoCodes'])->name('admin.promo_codes.index');
    });
});
