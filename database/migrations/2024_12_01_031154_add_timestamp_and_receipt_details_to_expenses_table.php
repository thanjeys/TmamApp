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
        Schema::table('expenses', function (Blueprint $table) {
            $table->dateTime('last_modified_time')->nullable();
            $table->string('expense_type')->nullable();
            $table->string('expense_receipt_name')->nullable();
            $table->string('expense_receipt_type')->nullable();
            $table->string('expense_receipt_file')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn([
                'last_modified_time',
                'expense_type',
                'expense_receipt_name',
                'expense_receipt_type',
                'expense_receipt_file',
            ]);
        });
    }
};
