<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class Customer extends Model
{

    use HasFactory, SoftDeletes;



    protected $fillable = [

        'name',

        'phone',

        'email',

        'gender',

        'date_of_birth',

        'address',

        'city',

        'state',

        'country',

        'customer_type',

        'credit_limit',

        'opening_balance',

        'balance_type',

        'blood_group',

        'allergies',

        'notes',

        'created_by',

        'updated_by',

        'deleted_by',
    ];





    protected $casts = [
        'date_of_birth'=> 'date',
        'credit_limit'=> 'decimal:2',
        'opening_balance'=> 'decimal:2',
    ];







    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */


    /**
     * Sales made to customer
     */
    public function sales()
    {

        return $this->hasMany(Sale::class);

    }




    /**
     * Future Sales Returns
     */
    public function salesReturns()
    {

        return $this->hasMany(SaleReturn::class);

    }

    
    /**
     * Future Payments
     */
    public function payments()
    {

        return $this->hasMany(Payment::class);

    }


    /**
     * Created By User
     */
    public function creator()
    {

        return $this->belongsTo(User::class, 'created_by');

    }




    /**
     * Updated By User
     */
    public function updater()
    {

        return $this->belongsTo(User::class, 'updated_by');

    }







    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */



    public function getTypeBadgeAttribute()
    {

        return match($this->customer_type)
        {


            'corporate' =>

                '<span class="badge bg-primary">
                    Corporate
                </span>',



            'walk_in' =>

                '<span class="badge bg-info">
                    Walk-in
                </span>',



            default =>

                '<span class="badge bg-success">
                    Regular
                </span>',


        };

    }






    public function getBalanceBadgeAttribute()
    {


        return match($this->balance_type)
        {


            'credit' =>

                '<span class="badge bg-success">
                    Credit
                </span>',



            default =>

                '<span class="badge bg-warning text-dark">
                    Debit
                </span>',


        };


    }






    public function getFormattedCreditLimitAttribute()
    {

        return number_format($this->credit_limit, 2);

    }







    public function getFullAddressAttribute()
    {

        return collect([$this->address, $this->city, $this->state, $this->country])
        ->filter()
        ->implode(', ');

    }






    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */



    public function isWalkIn()
    {

        return $this->customer_type === 'walk_in';

    }





    public function isCorporate()
    {

        return $this->customer_type === 'corporate';

    }



}