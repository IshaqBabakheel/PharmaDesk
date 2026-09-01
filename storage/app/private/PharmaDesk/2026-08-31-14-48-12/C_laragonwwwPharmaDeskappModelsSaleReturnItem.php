<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleReturnItem extends Model
{
    protected $fillable = [
        'sale_return_id',
        'sale_item_id',
        'medicine_id',
        'purchase_item_id',
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

    protected $casts = [
        'expiry_date' => 'date',
        'quantity' => 'integer',
        'free_quantity' => 'integer',
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function saleReturn()
    {
        return $this->belongsTo(SaleReturn::class);
    }

    public function saleItem()
    {
        return $this->belongsTo(SaleItem::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function purchaseItem()
    {
        return $this->belongsTo(PurchaseItem::class);
    }
}