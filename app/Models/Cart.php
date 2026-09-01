<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'customer_id',
        'status',
        'subtotal',
        'discount',
        'tax',
        'shipping',
        'other_charges',
        'grand_total',
        'paid_amount',
        'due_amount',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping' => 'decimal:2',
        'other_charges' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isHeld(): bool
    {
        return $this->status === 'held';
    }

    public function isCheckedOut(): bool
    {
        return $this->status === 'checked_out';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
}