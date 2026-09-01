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
        Schema::create('stock_adjustment_items', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Adjustment
            |--------------------------------------------------------------------------
            */
            $table->foreignId('stock_adjustment_id')->constrained('stock_adjustments')->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Medicine
            |--------------------------------------------------------------------------
            */
            $table->foreignId('medicine_id')->constrained('medicines')->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Target Batch
            |--------------------------------------------------------------------------
            |
            | Required for decreases.
            |
            | Optional for increases because an increase can create
            | a new adjustment batch.
            |
            */
            $table->foreignId('purchase_item_id')->nullable()->constrained('purchase_items')->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Batch Information
            |--------------------------------------------------------------------------
            */
            $table->string('batch_number')->nullable();
            $table->date('expiry_date')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Quantity
            |--------------------------------------------------------------------------
            */
            $table->integer('quantity');


            /*
            |--------------------------------------------------------------------------
            | Pricing Snapshot
            |--------------------------------------------------------------------------
            */
            $table->decimal('purchase_price', 15, 2)->default(0);
            $table->decimal('selling_price', 15, 2)->default(0);


            /*
            |--------------------------------------------------------------------------
            | Item Notes
            |--------------------------------------------------------------------------
            */
            $table->text('notes')->nullable();


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
            $table->index('stock_adjustment_id');
            $table->index('medicine_id');
            $table->index('purchase_item_id');
            $table->index('batch_number');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjustment_items');
    }
};