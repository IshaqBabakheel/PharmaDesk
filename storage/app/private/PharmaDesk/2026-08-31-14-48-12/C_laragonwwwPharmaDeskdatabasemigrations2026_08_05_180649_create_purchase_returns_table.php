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
        Schema::create('purchase_returns', function (Blueprint $table) {

            $table->id();

            $table->string('return_number')->unique();

            $table->foreignId('purchase_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('supplier_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->date('return_date');

            $table->decimal('subtotal', 15, 2)->default(0);

            $table->enum('discount_type', ['Fixed', 'Percentage'])
                ->default('Fixed');

            $table->decimal('discount', 15, 2)->default(0);

            $table->enum('tax_type', ['Fixed', 'Percentage'])
                ->default('Fixed');

            $table->decimal('tax', 15, 2)->default(0);

            $table->decimal('grand_total', 15, 2)->default(0);

            $table->enum('status', [

                'Completed',

                'Draft',

                'Cancelled',

            ])->default('Completed');

            $table->text('reason')->nullable();

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

            $table->index('return_number');
            $table->index('purchase_id');
            $table->index('supplier_id');
            $table->index('return_date');
            $table->index('status');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_returns');
    }
};