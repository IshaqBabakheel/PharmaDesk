<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase_items', function (Blueprint $table) {

            $table->id();


            /*
            |--------------------------------------------------------------------------
            | Relationships
            |--------------------------------------------------------------------------
            */

            $table->foreignId('purchase_id')
                ->nullable()
                ->constrained('purchases')
                ->nullOnDelete();

            $table->foreignId('medicine_id')
                ->constrained('medicines')
                ->restrictOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Source
            |--------------------------------------------------------------------------
            */

            $table->string('source')
                ->default('purchase');

            
            /*
            |--------------------------------------------------------------------------
            | Batch Information
            |--------------------------------------------------------------------------
            */

            $table->string('batch_number')
                ->nullable();

            $table->date('expiry_date')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Quantity
            |--------------------------------------------------------------------------
            */

            $table->integer('quantity');

            $table->integer('free_quantity')
                ->default(0);


            /*
            |--------------------------------------------------------------------------
            | Pricing
            |--------------------------------------------------------------------------
            */

            $table->decimal('purchase_price', 15, 2);

            $table->decimal('selling_price', 15, 2);

            $table->decimal('discount', 15, 2)
                ->default(0);

            $table->decimal('tax', 15, 2)
                ->default(0);

            $table->decimal('total', 15, 2);


            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('purchase_id');

            $table->index('medicine_id');

            $table->index('batch_number');

            $table->index('expiry_date');

            $table->index('source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};