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
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('organization_id');
            $table->string('currency_id')->unique();
            $table->string('currency_code');
            $table->string('currency_name');
            $table->string('currency_symbol')->nullable();
            $table->integer('price_precision');
            $table->string('currency_format')->nullable();
            $table->boolean('is_base_currency')->default(false);
            $table->decimal('exchange_rate', 10, 4)->nullable();
            $table->date('effective_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
