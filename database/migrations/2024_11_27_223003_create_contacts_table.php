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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('organization_id');
            $table->string('contact_id')->unique();
            $table->string('contact_name');
            $table->string('company_name')->nullable();
            $table->string('contact_type');
            $table->string('status');
            $table->integer('payment_terms');
            $table->string('payment_terms_label');
            $table->string('currency_id');
            $table->string('currency_code');
            $table->decimal('outstanding_receivable_amount', 10, 2)->default(0);
            $table->decimal('unused_credits_receivable_amount', 10, 2)->default(0);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
