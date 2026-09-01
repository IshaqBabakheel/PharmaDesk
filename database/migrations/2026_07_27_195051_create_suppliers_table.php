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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_code')->unique();

            $table->string('name');

            $table->string('company_name')->nullable();

            $table->string('contact_person')->nullable();

            $table->string('phone', 30);

            $table->string('alternate_phone', 30)->nullable();

            $table->string('email')->nullable();

            $table->string('website')->nullable();

            $table->text('address')->nullable();

            $table->string('city')->nullable();

            $table->string('state')->nullable();

            $table->string('country')->default('Pakistan');

            $table->string('postal_code')->nullable();

            $table->string('ntn')->nullable();

            $table->string('strn')->nullable();

            $table->decimal('opening_balance', 15, 2)->default(0);

            $table->enum('balance_type', ['Payable', 'Receivable'])
                ->default('Payable');

            $table->text('notes')->nullable();

            $table->boolean('status')->default(true);

            $table->integer('sort_order')->default(0);

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


            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
