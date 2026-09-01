<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'payment_number',
        'type',
        'sale_id',
        'purchase_id',
        'customer_id',
        'supplier_id',
        'expense_id',
        'amount',
        'method',
        'payment_date',
        'reference_number',
        'notes',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (Payment $payment) {
            if (empty($payment->payment_number)) {
                $lastId = (static::withTrashed()->max('id') ?? 0) + 1;

                $payment->payment_number =
                    'PAY-' . str_pad(
                        $lastId,
                        6,
                        '0',
                        STR_PAD_LEFT
                    );
            }
        });
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
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

    public function isReceipt(): bool
    {
        return $this->type === 'receipt';
    }

    public function isPayment(): bool
    {
        return $this->type === 'payment';
    }

    public function isCash(): bool
    {
        return $this->method === 'cash';
    }
}