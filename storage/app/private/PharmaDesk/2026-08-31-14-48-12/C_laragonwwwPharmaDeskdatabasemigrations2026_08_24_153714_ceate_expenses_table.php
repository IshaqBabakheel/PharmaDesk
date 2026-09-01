<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            $table->string('expense_number')->unique();

            $table->foreignId('expense_category_id')
                ->constrained('expense_categories')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->date('expense_date');

            $table->string('title');

            $table->text('description')->nullable();

            $table->decimal('amount', 15, 2);

            $table->decimal('paid_amount', 15, 2)->default(0);

            $table->decimal('due_amount', 15, 2)->default(0);

            $table->enum('payment_status', [
                'Unpaid',
                'Partially Paid',
                'Paid',
            ])->default('Unpaid');

            $table->enum('status', [
                'Completed',
                'Draft',
                'Cancelled',
            ])->default('Completed');

            $table->enum('payment_method', [
                'cash',
                'card',
                'bank_transfer',
                'jazzcash',
                'easypaisa',
                'other',
            ])->default('cash');

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

            $table->index('expense_number');
            $table->index('expense_category_id');
            $table->index('expense_date');
            $table->index('payment_status');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};