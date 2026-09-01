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
        Schema::table('purchases', function (Blueprint $table) {
            $table->boolean('stock_applied')->default(false)->after('status');
            // Or if you want a timestamp:
            // $table->timestamp('stock_applied_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('stock_applied');
            // Or if you used timestamp:
            // $table->dropColumn('stock_applied_at');
        });
    }
};