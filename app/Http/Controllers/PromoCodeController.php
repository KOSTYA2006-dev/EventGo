<?php

namespace App\Http\Controllers;

use App\Models\PromoCode;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    public function check(Request $request)
    {
        $code = $request->get('code');
        $amount = $request->get('amount', 0);
        
        if (!$code) {
            return response()->json([
                'valid' => false,
                'message' => 'Промокод не указан'
            ], 400);
        }

        $promoCode = PromoCode::where('code', $code)->first();

        if (!$promoCode) {
            return response()->json([
                'valid' => false,
                'message' => 'Промокод не найден'
            ]);
        }

        if (!$promoCode->isValid()) {
            return response()->json([
                'valid' => false,
                'message' => 'Промокод недействителен или истек срок действия'
            ]);
        }

        // Расчет скидки
        $discountAmount = $promoCode->calculateDiscount($amount);
        $discountText = $promoCode->discount_type === 'percentage' 
            ? $promoCode->discount_value . '%' 
            : number_format($promoCode->discount_value, 2, '.', ' ') . ' ₽';

        return response()->json([
            'valid' => true,
            'promo_code' => [
                'id' => $promoCode->id,
                'code' => $promoCode->code,
                'discount_type' => $promoCode->discount_type,
                'discount_value' => $promoCode->discount_value,
            ],
            'discount_type' => $promoCode->discount_type,
            'discount_value' => $promoCode->discount_value,
            'discount_amount' => $discountAmount,
            'discount_text' => $discountText,
        ]);
    }
}

