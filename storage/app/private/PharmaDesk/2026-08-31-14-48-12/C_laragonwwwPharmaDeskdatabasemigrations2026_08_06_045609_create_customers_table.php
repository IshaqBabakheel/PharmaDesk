<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    public function up(): void
    {

        Schema::create('customers', function (Blueprint $table) {


            $table->id();



            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */


            $table->string('name');


            $table->string('phone')
                ->nullable()
                ->index();


            $table->string('email')
                ->nullable()
                ->unique();



            $table->enum('gender', [

                'male',
                'female',
                'other'

            ])
                ->nullable();



            $table->date('date_of_birth')
                ->nullable();




            /*
            |--------------------------------------------------------------------------
            | Address Information
            |--------------------------------------------------------------------------
            */


            $table->text('address')
                ->nullable();



            $table->string('city')
                ->nullable();



            $table->string('state')
                ->nullable();



            $table->string('country')
                ->default('Pakistan');





            /*
            |--------------------------------------------------------------------------
            | Customer Type
            |--------------------------------------------------------------------------
            |
            | regular:
            | Normal customer
            |
            | walk_in:
            | Counter sale customer
            |
            | corporate:
            | Companies / organizations
            |
            */


            $table->enum('customer_type', [

                'credit',
                'regular',
                'walk_in',
                'corporate'

            ])
                ->default('regular');






            /*
            |--------------------------------------------------------------------------
            | Credit Management
            |--------------------------------------------------------------------------
            */


            $table->decimal(
                'credit_limit',
                15,
                2
            )
                ->default(0);



            $table->decimal(
                'opening_balance',
                15,
                2
            )
                ->default(0);



            $table->enum('balance_type', [

                'debit',
                'credit'

            ])
                ->default('debit');







            /*
            |--------------------------------------------------------------------------
            | Medical Information
            |--------------------------------------------------------------------------
            */


            $table->string('blood_group')
                ->nullable();



            $table->text('allergies')
                ->nullable();



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

        Schema::dropIfExists('customers');
    }
};
