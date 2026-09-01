<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('expense_id')
                ->nullable()
                ->after('purchase_id')
                ->constrained('expenses')
                ->nullOnDelete();

            $table->index('expense_id');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['expense_id']);
            $table->dropIndex(['expense_id']);
            $table->dropColumn('expense_id');
        });
    }
};