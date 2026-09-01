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
        Schema::create('sale_return_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sale_return_id')
                ->constrained('sale_returns')
                ->cascadeOnDelete();

            $table->foreignId('sale_item_id')
                ->constrained('sale_items')
                ->restrictOnDelete();

            $table->foreignId('medicine_id')
                ->constrained('medicines')
                ->restrictOnDelete();

            $table->foreignId('purchase_item_id')
                ->nullable()
                ->constrained('purchase_items')
                ->nullOnDelete();

            $table->string('batch_number')->nullable();

            $table->date('expiry_date')->nullable();

            $table->unsignedInteger('quantity')->default(0);

            $table->unsignedInteger('free_quantity')->default(0);

            $table->decimal('purchase_price', 15, 2)->default(0);

            $table->decimal('selling_price', 15, 2)->default(0);

            $table->decimal('discount', 15, 2)->default(0);

            $table->decimal('tax', 15, 2)->default(0);

            $table->decimal('total', 15, 2)->default(0);

            $table->timestamps();

            $table->index('medicine_id');
            $table->index('purchase_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_return_items');
    }
};
