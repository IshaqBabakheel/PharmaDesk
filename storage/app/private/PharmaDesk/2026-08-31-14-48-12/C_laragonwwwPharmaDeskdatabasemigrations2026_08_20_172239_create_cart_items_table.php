<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cart_id')
                ->constrained('carts')
                ->cascadeOnDelete();

            $table->foreignId('medicine_id')
                ->constrained('medicines')
                ->restrictOnDelete();

            $table->integer('quantity')->default(1);
            $table->integer('free_quantity')->default(0);

            $table->decimal('purchase_price', 15, 2)->default(0);
            $table->decimal('selling_price', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);

            $table->timestamps();

            $table->index('cart_id');
            $table->index('medicine_id');

            $table->unique([
                'cart_id',
                'medicine_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};