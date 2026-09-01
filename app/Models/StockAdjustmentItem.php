<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAdjustmentItem extends Model
{
    protected $fillable = [
        'stock_adjustment_id',
        'medicine_id',
        'purchase_item_id',
        'batch_number',
        'expiry_date',
        'quantity',
        'purchase_price',
        'selling_price',
        'notes',
    ];


    protected $casts = [
        'expiry_date' => 'date',
        'quantity' => 'integer',
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
    ];


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function stockAdjustment()
    {
        return $this->belongsTo(StockAdjustment::class);
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