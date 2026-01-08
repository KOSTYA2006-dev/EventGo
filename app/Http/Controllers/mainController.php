<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class mainController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('is_active', true)->get();
        $eventDate = config('app.event_date', '2026-10-10 18:00:00');

        return view('index', compact('tickets', 'eventDate'));
    }
}
