<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'medicine_id',
        'quantity',
        'free_quantity',
        'purchase_price',
        'selling_price',
        'discount',
        'tax',
        'total',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'free_quantity' => 'integer',
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}