<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Страница с реквизитами
     */
    public function requisites()
    {
        return view('pages.requisites');
    }

    /**
     * Пользовательское соглашение / Оферта
     */
    public function agreement()
    {
        return view('pages.agreement');
    }

    /**
     * Информация о доставке / получении заказа
     */
    public function delivery()
    {
        return view('pages.delivery');
    }

    /**
     * Контакты
     */
    public function contacts()
    {
        return view('pages.contacts');
    }
}

