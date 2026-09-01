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
        Schema::create('manufacturers', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            $table->string('contact_person')->nullable();

            $table->string('phone', 30)->nullable();

            $table->string('email')->nullable();

            $table->string('website')->nullable();

            $table->text('address')->nullable();

            $table->string('city')->nullable();

            $table->string('country')->nullable();

            $table->text('notes')->nullable();

            $table->boolean('status')->default(true);

            $table->integer('sort_order')->default(0);

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletes();
            $table->timestamps(); 
            $table->index('status');
            $table->index('sort_order');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manufacturers');
    }
};
