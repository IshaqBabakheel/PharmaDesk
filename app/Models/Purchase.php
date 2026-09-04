<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Purchase extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'purchase_number',
        'invoice_number',
        'reference_number',
        'supplier_id',
        'purchase_date',
        'subtotal',
        'discount_type',
        'discount',
        'tax_type',
        'tax',
        'shipping',
        'other_charges',
        'grand_total',
        'paid_amount',
        'due_amount',
        'payment_status',
        'status',
        'stock_applied',
        'notes',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Attribute casting.
     */
    protected function casts(): array
    {
        return [

            'purchase_date' => 'date',

            'subtotal' => 'decimal:2',

            'discount' => 'decimal:2',

            'tax' => 'decimal:2',

            'shipping' => 'decimal:2',

            'other_charges' => 'decimal:2',

            'grand_total' => 'decimal:2',

            'paid_amount' => 'decimal:2',

            'due_amount' => 'decimal:2',

            'stock_applied' => 'boolean',

        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('purchases')
            ->logOnly([
                'supplier_id',
                'purchase_date',
                'subtotal',
                'discount',
                'tax',
                'grand_total',
                'paid_amount',
                'due_amount',
                'payment_status',
                'status',
                'notes',
            ])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    /**
     * Boot model.
     */
    protected static function booted(): void
    {
        static::creating(function ($purchase) {

            if (empty($purchase->purchase_number)) {

                $purchase->purchase_number = self::generatePurchaseNumber();

            }

        });
    }

    /**
     * Generate purchase number.
     */
    private static function generatePurchaseNumber(): string
    {
        $prefix = 'PUR-' . now()->format('Ymd') . '-';

        $lastPurchase = self::whereDate(
                'created_at',
                today()
            )
            ->latest()
            ->first();

        $number = 1;

        if ($lastPurchase) {

            $last = explode('-', $lastPurchase->purchase_number);

            $number = (int) end($last) + 1;

        }

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /*
    |--------------------------------------------------------------------------
    | Business Helpers
    |--------------------------------------------------------------------------
    */
    public function isDraft()
    {
        return $this->status === 'Draft';
    }

    public function isCompleted()
    {
        return $this->status === 'Completed';
    }

    public function isCancelled()
    {
        return $this->status === 'Cancelled';
    }

    public function calculateDue()
    {
        return max($this->grand_total - $this->paid_amount, 0);
    }

    public function isStockApplied(): bool
    {
        return $this->stock_applied === true;
    }

    public function isPaid()
    {
        return $this->payment_status === 'Paid';
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
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
}