<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Sale extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [

        'invoice_number',

        'customer_id',

        'doctor_name',

        'sale_date',

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

        'notes',

        'created_by',

        'updated_by',

        'deleted_by',
    ];



    protected $casts = [

        'sale_date' => 'date',

        'subtotal' => 'decimal:2',

        'discount' => 'decimal:2',

        'tax' => 'decimal:2',

        'shipping' => 'decimal:2',

        'other_charges' => 'decimal:2',

        'grand_total' => 'decimal:2',

        'paid_amount' => 'decimal:2',

        'due_amount' => 'decimal:2',

    ];




    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function returns()
    {
        return $this->hasMany(SaleReturn::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }



    public function items()
    {
        return $this->hasMany(SaleItem::class);
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
    | Accessors
    |--------------------------------------------------------------------------
    */


    public function getStatusBadgeAttribute()
    {

        return match($this->status)
        {

            'completed' =>
                '<span class="badge bg-success">
                    Completed
                </span>',


            'cancelled' =>
                '<span class="badge bg-danger">
                    Cancelled
                </span>',


            default =>
                '<span class="badge bg-warning text-dark">
                    Draft
                </span>'

        };

    }




    public function getPaymentStatusBadgeAttribute()
    {

        return match($this->payment_status)
        {


            'paid' =>

                '<span class="badge bg-success">
                    Paid
                </span>',



            'partial' =>

                '<span class="badge bg-warning text-dark">
                    Partial
                </span>',



            default =>

                '<span class="badge bg-danger">
                    Due
                </span>'


        };

    }




    public function getFormattedTotalAttribute()
    {

        return number_format($this->grand_total, 2);

    }




    public function getFormattedDueAttribute()
    {

        return number_format($this->due_amount, 2);

    }





    /*
    |--------------------------------------------------------------------------
    | Business Helpers
    |--------------------------------------------------------------------------
    */



    public function isDraft()
    {

        return $this->status === 'draft';

    }



    public function isCompleted()
    {

        return $this->status === 'completed';

    }



    public function isCancelled()
    {

        return $this->status === 'cancelled';

    }



    public function calculateDue()
    {

        return max($this->grand_total - $this->paid_amount, 0);

    }


}



