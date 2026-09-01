<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicine extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * --------------------------------------------------------------------------
     * Mass Assignable
     * --------------------------------------------------------------------------
     */
    protected $fillable = [

        'name',
        'generic_name',
        'sku',
        'barcode',
        'medicine_code',
        'medicine_category_id',
        'medicine_type_id',
        'manufacturer_id',
        'unit_id',
        'purchase_price',
        'selling_price',
        'wholesale_price',
        'opening_stock',
        'current_stock',
        'minimum_stock',
        'maximum_stock',
        'reorder_level',
        'has_expiry',
        'shelf_life_months',
        'tax_percentage',
        'image',
        'description',
        'status',
        'sort_order',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * --------------------------------------------------------------------------
     * Casts
     * --------------------------------------------------------------------------
     */
    protected function casts(): array
    {
        return [
            'purchase_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'wholesale_price' => 'decimal:2',
            'opening_stock' => 'decimal:2',
            'current_stock' => 'decimal:2',
            'minimum_stock' => 'decimal:2',
            'maximum_stock' => 'decimal:2',
            'reorder_level' => 'decimal:2',
            'tax_percentage' => 'decimal:2',
            'has_expiry' => 'boolean',
            'status' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(MedicineCategory::class, 'medicine_category_id');
    }

    public function type()
    {
        return $this->belongsTo(MedicineType::class, 'medicine_type_id');
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
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

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('current_stock', '>', 0);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn(
            'current_stock',
            '<=',
            'reorder_level'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getStockStatusAttribute()
    {
        if ($this->current_stock <= 0) {

            return 'Out of Stock';
        }

        if ($this->current_stock <= $this->reorder_level) {

            return 'Low Stock';
        }

        return 'Available';
    }

    public function getProfitAttribute()
    {
        return $this->selling_price - $this->purchase_price;
    }

    /**
     * Boot Model
     */
    protected static function booted(): void
    {
        static::creating(function (Medicine $medicine) {

            $nextId = (static::max('id') ?? 0) + 1;

            /*
        |--------------------------------------------------------------------------
        | SKU
        |--------------------------------------------------------------------------
        */

            if (empty($medicine->sku)) {

                $medicine->sku = 'MED-' . str_pad(
                    $nextId,
                    6,
                    '0',
                    STR_PAD_LEFT
                );
            }

            /*
        |--------------------------------------------------------------------------
        | Medicine Code
        |--------------------------------------------------------------------------
        */

            if (empty($medicine->medicine_code)) {

                $medicine->medicine_code = 'MD' . str_pad(
                    $nextId,
                    6,
                    '0',
                    STR_PAD_LEFT
                );
            }

            /*
        |--------------------------------------------------------------------------
        | Barcode
        |--------------------------------------------------------------------------
        */

            if (empty($medicine->barcode)) {

                do {

                    $barcode = fake()->unique()->numerify('890##########');
                } while (static::where('barcode', $barcode)->exists());

                $medicine->barcode = $barcode;
            }

            /*
        |--------------------------------------------------------------------------
        | Current Stock
        |--------------------------------------------------------------------------
        */

            $medicine->current_stock = $medicine->opening_stock;
        });
    }
}
