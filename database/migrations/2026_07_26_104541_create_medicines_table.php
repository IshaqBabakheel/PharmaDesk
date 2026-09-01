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
        Schema::create('medicines', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */

            $table->string('name');

            $table->string('generic_name')->nullable();

            $table->string('sku')->unique();

            $table->string('barcode')->nullable()->unique();

            $table->string('medicine_code')->nullable()->unique();

            /*
            |--------------------------------------------------------------------------
            | Relationships
            |--------------------------------------------------------------------------
            */

            $table->foreignId('medicine_category_id')
                ->constrained('medicine_categories')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('medicine_type_id')
                ->constrained('medicine_types')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('manufacturer_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('unit_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Pricing
            |--------------------------------------------------------------------------
            */

            $table->decimal('purchase_price', 12, 2)->default(0);

            $table->decimal('selling_price', 12, 2)->default(0);

            $table->decimal('wholesale_price', 12, 2)->default(0);

            /*
            |--------------------------------------------------------------------------
            | Stock
            |--------------------------------------------------------------------------
            */

            $table->decimal('opening_stock', 12, 2)->default(0);

            $table->decimal('current_stock', 12, 2)->default(0);

            $table->decimal('minimum_stock', 12, 2)->default(0);

            $table->decimal('maximum_stock', 12, 2)->default(0);

            $table->decimal('reorder_level', 12, 2)->default(0);

            /*
            |--------------------------------------------------------------------------
            | Expiry
            |--------------------------------------------------------------------------
            */

            $table->boolean('has_expiry')->default(true);

            $table->unsignedSmallInteger('shelf_life_months')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Tax
            |--------------------------------------------------------------------------
            */

            $table->decimal('tax_percentage', 5, 2)->default(0);

            /*
            |--------------------------------------------------------------------------
            | Other
            |--------------------------------------------------------------------------
            */

            $table->string('image')->nullable();

            $table->longText('description')->nullable();

            $table->boolean('status')->default(true);

            $table->integer('sort_order')->default(0);

            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('name');

            $table->index('generic_name');

            $table->index('status');

            $table->index('current_stock');

            $table->index('barcode');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};