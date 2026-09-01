<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [

        'supplier_code',

        'name',

        'company_name',

        'contact_person',

        'phone',

        'alternate_phone',

        'email',

        'website',

        'address',

        'city',

        'state',

        'country',

        'postal_code',

        'ntn',

        'strn',

        'opening_balance',

        'balance_type',

        'notes',

        'status',

        'sort_order',

        'created_by',

        'updated_by',

        'deleted_by',

    ];

    protected $casts = [

        'status'=>'boolean',

        'opening_balance'=>'decimal:2',

    ];

    protected static function booted()
    {
        static::creating(function ($supplier) {

            $lastId = static::max('id') + 1;

            $supplier->supplier_code =
                'SUP-' . str_pad($lastId,5,'0',STR_PAD_LEFT);

        });
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class,'updated_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class,'deleted_by');
    }
}