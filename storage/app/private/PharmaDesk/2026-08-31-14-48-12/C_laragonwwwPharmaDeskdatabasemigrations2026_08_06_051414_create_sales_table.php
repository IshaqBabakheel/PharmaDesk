<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {

            $table->id();

            $table->string('invoice_number')
                ->unique();

            $table->foreignId('customer_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();


            $table->string('doctor_name')
                ->nullable();


            $table->date('sale_date');


            /*
            |--------------------------------------------------------------------------
            | Amounts
            |--------------------------------------------------------------------------
            */

            $table->decimal('subtotal',12,2)
                ->default(0);


            $table->enum('discount_type',[
                'fixed',
                'percentage'
            ])
            ->default('fixed');


            $table->decimal('discount',12,2)
                ->default(0);


            $table->enum('tax_type',[
                'fixed',
                'percentage'
            ])
            ->default('fixed');


            $table->decimal('tax',12,2)
                ->default(0);


            $table->decimal('shipping',12,2)
                ->default(0);


            $table->decimal('other_charges',12,2)
                ->default(0);


            $table->decimal('grand_total',12,2)
                ->default(0);



            /*
            |--------------------------------------------------------------------------
            | Payment
            |--------------------------------------------------------------------------
            */

            $table->decimal('paid_amount',12,2)
                ->default(0);


            $table->decimal('due_amount',12,2)
                ->default(0);


            $table->enum('payment_status',[
                'paid',
                'partial',
                'due'
            ])
            ->default('due');



            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->enum('status',[
                'draft',
                'completed',
                'cancelled'
            ])
            ->default('draft');



            $table->text('notes')
                ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();


            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('deleted_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();    



            $table->timestamps();

            $table->softDeletes();

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};