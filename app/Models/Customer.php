<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'activity_type',
        'personal_data_agreement',
    ];

    protected $casts = [
        'personal_data_agreement' => 'boolean',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getActivityTypeLabelAttribute(): string
    {
        return match($this->activity_type) {
            'podologist' => 'Подолог',
            'aesthetician' => 'Эстетист',
            'manager' => 'Руководитель',
            default => $this->activity_type,
        };
    }
}
