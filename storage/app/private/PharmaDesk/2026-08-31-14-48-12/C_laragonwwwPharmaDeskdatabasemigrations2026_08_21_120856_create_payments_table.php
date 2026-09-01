<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->string('payment_number')->unique();

            $table->enum('type', [
                'receipt',
                'payment',
            ]);

            $table->foreignId('sale_id')
                ->nullable()
                ->constrained('sales')
                ->nullOnDelete();

            $table->foreignId('purchase_id')
                ->nullable()
                ->constrained('purchases')
                ->nullOnDelete();

            $table->foreignId('customer_id')
                ->nullable()
                ->constrained('customers')
                ->nullOnDelete();

            $table->foreignId('supplier_id')
                ->nullable()
                ->constrained('suppliers')
                ->nullOnDelete();

            $table->decimal('amount', 15, 2);

            $table->enum('method', [
                'cash',
                'card',
                'bank_transfer',
                'jazzcash',
                'easypaisa',
                'other',
            ])->default('cash');

            $table->date('payment_date');

            $table->string('reference_number')->nullable();

            $table->text('notes')->nullable();

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

            $table->index('payment_number');
            $table->index('type');
            $table->index('sale_id');
            $table->index('purchase_id');
            $table->index('customer_id');
            $table->index('supplier_id');
            $table->index('payment_date');
            $table->index('method');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};