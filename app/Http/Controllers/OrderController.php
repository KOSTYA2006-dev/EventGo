<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Ticket;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function showForm($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        return view('order.form', compact('ticket'));
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ticket_id' => 'required|exists:tickets,id',
            'quantity' => 'required|integer|min:1',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'activity_type' => 'required|in:podologist,aesthetician,manager',
            'personal_data_agreement' => 'required|accepted',
            'promo_code' => 'nullable|string|exists:promo_codes,code',
            'payment_method' => 'required|in:qr,sbp',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $ticket = Ticket::findOrFail($request->ticket_id);
            
            // Проверка доступности билетов
            if ($ticket->available_quantity < $request->quantity) {
                return back()->withErrors(['quantity' => 'Недостаточно доступных билетов'])->withInput();
            }

            // Создание или поиск покупателя
            $customer = Customer::firstOrCreate(
                ['email' => $request->email],
                [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'phone' => $request->phone,
                    'activity_type' => $request->activity_type,
                    'personal_data_agreement' => true,
                ]
            );

            // Обновление данных покупателя если они изменились
            $customer->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'activity_type' => $request->activity_type,
                'personal_data_agreement' => true,
            ]);

            // Расчет суммы
            $ticketPrice = $ticket->price;
            $subtotal = $ticketPrice * $request->quantity;
            $discountAmount = 0;
            $promoCode = null;

            // Применение промокода
            if ($request->promo_code) {
                $promoCode = PromoCode::where('code', $request->promo_code)->first();
                if ($promoCode && $promoCode->isValid()) {
                    $discountAmount = $promoCode->calculateDiscount($subtotal);
                    $promoCode->increment('used_count');
                }
            }

            $totalAmount = $subtotal - $discountAmount;

            // Создание заказа
            $order = Order::create([
                'customer_id' => $customer->id,
                'ticket_id' => $ticket->id,
                'quantity' => $request->quantity,
                'ticket_price' => $ticketPrice,
                'promo_code_id' => $promoCode?->id,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
            ]);

            // Уменьшение доступного количества билетов
            $ticket->decrement('available_quantity', $request->quantity);

            DB::commit();

            return redirect()->route('payment.show', $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Произошла ошибка при создании заказа: ' . $e->getMessage()])->withInput();
        }
    }

    public function show($id)
    {
        $order = Order::with(['customer', 'ticket', 'promoCode'])->findOrFail($id);
        return view('order.show', compact('order'));
    }
}
