<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseItem extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'purchase_id',
        'source',
        'medicine_id',
        'batch_number',
        'expiry_date',
        'quantity',
        'free_quantity',
        'purchase_price',
        'selling_price',
        'discount',
        'tax',
        'total',
    ];

    /**
     * Attribute casting.
     */
    protected function casts(): array
    {
        return [
            'expiry_date' => 'date',
            'quantity' => 'integer',
            'free_quantity' => 'integer',
            'purchase_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'discount' => 'decimal:2',
            'tax' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}