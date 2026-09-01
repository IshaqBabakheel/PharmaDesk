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
        Schema::create('purchases', function (Blueprint $table) {

            $table->id();

            // Purchase Information
            $table->string('purchase_number')->unique();
            $table->string('invoice_number')->nullable();
            $table->string('reference_number')->nullable();

            // Supplier
            $table->foreignId('supplier_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Dates
            $table->date('purchase_date');

            // Totals
            $table->decimal('subtotal', 15, 2)->default(0);

            $table->enum('discount_type', [
                'Fixed',
                'Percentage',
            ])->default('Fixed');

            $table->decimal('discount', 15, 2)->default(0);

            $table->enum('tax_type', [
                'Fixed',
                'Percentage',
            ])->default('Percentage');

            $table->decimal('tax', 15, 2)->default(0);

            $table->decimal('shipping', 15, 2)->default(0);

            $table->decimal('other_charges', 15, 2)->default(0);

            $table->decimal('grand_total', 15, 2)->default(0);

            // Payment
            $table->decimal('paid_amount', 15, 2)->default(0);

            $table->decimal('due_amount', 15, 2)->default(0);

            $table->enum('payment_status', [

                'Paid',

                'Partially Paid',

                'Unpaid',

            ])->default('Unpaid');

            // Purchase Status
            $table->enum('status', [

                'Draft',

                'Completed',

                'Cancelled',

            ])->default('Completed');

            // Notes
            $table->text('notes')->nullable();

            // Audit
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

            // Indexes
            $table->index('purchase_number');
            $table->index('purchase_date');
            $table->index('invoice_number');
            $table->index('supplier_id');
            $table->index('status');
            $table->index('payment_status');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};