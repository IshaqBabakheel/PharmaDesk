<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseReturn extends Model
{
    use SoftDeletes;

    protected $fillable = [

        'return_number',

        'purchase_id',

        'supplier_id',

        'return_date',

        'subtotal',

        'discount_type',

        'discount',

        'tax_type',

        'tax',

        'grand_total',

        'status',

        'stock_applied',

        'reason',

        'notes',

        'created_by',

        'updated_by',

        'deleted_by',

    ];

    protected $casts = [

        'return_date' => 'date',
        'stock_applied' => 'boolean',

    ];

    protected static function booted()
    {
        static::creating(function ($return) {

            if (empty($return->return_number)) {

                $lastId = static::withTrashed()->max('id') + 1;

                $return->return_number = 'PR-' . str_pad($lastId, 6, '0', STR_PAD_LEFT);

            }

        });
    }

    public function isStockApplied(): bool
    {
        return $this->stock_applied;
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

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseReturnItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}