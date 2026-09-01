<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
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
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */


    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function purchaseItem()
    {
        return $this->belongsTo(PurchaseItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */


    public function getSubTotalAttribute()
    {
        return $this->quantity * $this->selling_price;
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total, 2);
    }

    public function getIsExpiredAttribute()
    {
        return now()->greaterThan($this->expiry_date);
    }

}