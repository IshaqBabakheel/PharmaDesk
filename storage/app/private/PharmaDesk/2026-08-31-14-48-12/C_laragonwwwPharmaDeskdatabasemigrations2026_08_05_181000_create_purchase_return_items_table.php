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
        Schema::create('purchase_return_items', function (Blueprint $table) {

            $table->id();

            $table->foreignId('purchase_return_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('purchase_item_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('medicine_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('batch_number')->nullable();

            $table->date('expiry_date')->nullable();

            $table->integer('quantity');

            $table->decimal('purchase_price', 15, 2);

            $table->decimal('total', 15, 2);

            $table->timestamps();

            $table->index('purchase_return_id');
            $table->index('purchase_item_id');
            $table->index('medicine_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_return_items');
    }
};