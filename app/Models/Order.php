<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'customer_id',
        'ticket_id',
        'quantity',
        'ticket_price',
        'promo_code_id',
        'discount_amount',
        'total_amount',
        'payment_method',
        'payment_status',
        'payment_id',
        'payment_receipt_path',
        'receipt_sent',
        'ticket_sent',
        'ticket_path',
        'notes',
    ];

    protected $casts = [
        'ticket_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'receipt_sent' => 'boolean',
        'ticket_sent' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(Str::random(8));
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function promoCode(): BelongsTo
    {
        return $this->belongsTo(PromoCode::class);
    }

    public function getFormattedTotalAmountAttribute(): string
    {
        return number_format($this->total_amount, 2, '.', ' ') . ' ₽';
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match($this->payment_status) {
            'pending' => 'Ожидает оплаты',
            'paid' => 'Оплачен',
            'failed' => 'Ошибка оплаты',
            'cancelled' => 'Отменен',
            default => $this->payment_status,
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match($this->payment_method) {
            'qr' => 'QR код',
            'sbp' => 'СБП',
            default => $this->payment_method,
        };
    }
}
