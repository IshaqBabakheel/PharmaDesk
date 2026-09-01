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
        Schema::create('stock_adjustments', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Adjustment Number
            |--------------------------------------------------------------------------
            */
            $table->string('adjustment_number')->unique();


            /*
            |--------------------------------------------------------------------------
            | Adjustment Type
            |--------------------------------------------------------------------------
            */
            $table->enum('type', ['increase', 'decrease',]);


            /*
            |--------------------------------------------------------------------------
            | Adjustment Date
            |--------------------------------------------------------------------------
            */

            $table->date('adjustment_date');


            /*
            |--------------------------------------------------------------------------
            | Reason
            |--------------------------------------------------------------------------
            */

            $table->string('reason');


            /*
            |--------------------------------------------------------------------------
            | Notes
            |--------------------------------------------------------------------------
            */

            $table->text('notes')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            |
            | Draft:
            |   No stock effect.
            |
            | Completed:
            |   Stock effect applied.
            |
            | Cancelled:
            |   No active stock effect.
            |
            */

            $table->enum('status', ['draft', 'completed', 'cancelled',])->default('draft');


            /*
            |--------------------------------------------------------------------------
            | Stock Application State
            |--------------------------------------------------------------------------
            */

            $table->boolean('stock_applied')->default(false);


            /*
            |--------------------------------------------------------------------------
            | Audit Fields
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


            /*
            |--------------------------------------------------------------------------
            | Timestamps / Soft Delete
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

            $table->softDeletes();


            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('adjustment_number');
            $table->index('type');
            $table->index('adjustment_date');
            $table->index('status');
            $table->index('stock_applied');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjustments');
    }
};