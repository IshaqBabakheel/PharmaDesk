<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'expense_number',
        'expense_category_id',
        'expense_date',
        'title',
        'description',
        'amount',
        'paid_amount',
        'due_amount',
        'payment_status',
        'status',
        'payment_method',
        'reference_number',
        'notes',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (Expense $expense) {
            if (empty($expense->expense_number)) {
                $lastId = (static::withTrashed()->max('id') ?? 0) + 1;

                $expense->expense_number =
                    'EXP-' . str_pad(
                        $lastId,
                        6,
                        '0',
                        STR_PAD_LEFT
                    );
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
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

    public function isDraft(): bool
    {
        return $this->status === 'Draft';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'Completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'Cancelled';
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'Paid';
    }

    public function calculateDue(): float
    {
        return max(
            (float) $this->amount -
            (float) $this->paid_amount,
            0
        );
    }
}