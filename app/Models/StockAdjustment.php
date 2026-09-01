<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockAdjustment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'adjustment_number',
        'type',
        'adjustment_date',
        'reason',
        'notes',
        'status',
        'stock_applied',
        'created_by',
        'updated_by',
        'deleted_by',
    ];


    protected $casts = [
        'adjustment_date' => 'date',
        'stock_applied' => 'boolean',
    ];


    /*
    |--------------------------------------------------------------------------
    | Boot
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::creating(function (StockAdjustment $adjustment) {

            if (empty($adjustment->adjustment_number)) {

                $lastId = static::withTrashed()->max('id') + 1;

                $adjustment->adjustment_number =
                    'ADJ-' .
                    now()->format('Ymd') .
                    '-' .
                    str_pad(
                        $lastId,
                        4,
                        '0',
                        STR_PAD_LEFT
                    );
            }

        });
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function items()
    {
        return $this->hasMany(StockAdjustmentItem::class);
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


    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }


    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }


    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }


    public function isStockApplied(): bool
    {
        return $this->stock_applied;
    }
}