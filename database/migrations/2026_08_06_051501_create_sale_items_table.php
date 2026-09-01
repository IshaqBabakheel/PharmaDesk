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
        Schema::create('sale_items', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Relationships
            |--------------------------------------------------------------------------
            */

            $table->foreignId('sale_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('medicine_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('purchase_item_id')
                ->nullable()
                ->constrained('purchase_items')
                ->nullOnDelete();


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

            $table->decimal('purchase_price', 12, 2);

            $table->decimal('selling_price', 12, 2);

            $table->decimal('discount', 12, 2)
                ->default(0);

            $table->decimal('tax', 12, 2)
                ->default(0);

            $table->decimal('total', 12, 2);


            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};