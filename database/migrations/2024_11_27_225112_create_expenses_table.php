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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('organization_id');
            $table->bigInteger('expense_id')->unique();
            $table->date('date');
            $table->string('account_name');
            $table->string('description');
            $table->string('currency_id');
            $table->string('currency_code');
            $table->decimal('bcy_total', 10, 2);
            $table->decimal('bcy_total_without_tax', 10, 2);
            $table->decimal('total', 10, 2);
            $table->decimal('total_without_tax', 10, 2);
            $table->boolean('is_billable')->default(true);
            $table->string('reference_number')->nullable();
            $table->string('customer_id');
            $table->string('customer_name');
            $table->string('status');
            $table->timestamp('created_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
