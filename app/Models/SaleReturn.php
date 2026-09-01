<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleReturn extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sale_id',
        'customer_id',
        'return_number',
        'return_date',
        'subtotal',
        'discount',
        'tax',
        'other_charges',
        'grand_total',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'return_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'other_charges' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(SaleReturnItem::class);
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