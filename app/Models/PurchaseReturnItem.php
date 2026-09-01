<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturnItem extends Model
{
    protected $fillable = [

        'purchase_return_id',

        'purchase_item_id',

        'medicine_id',

        'batch_number',

        'expiry_date',

        'quantity',

        'purchase_price',

        'total',

    ];

    protected $casts = [

        'expiry_date' => 'date',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function purchaseReturn()
    {
        return $this->belongsTo(PurchaseReturn::class);
    }

    public function purchaseItem()
    {
        return $this->belongsTo(PurchaseItem::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}