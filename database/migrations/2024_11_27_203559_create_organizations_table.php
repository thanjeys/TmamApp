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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->bigInteger('organization_id')->unique();
            $table->string('name');
            $table->string('contact_name')->nullable();
            $table->string('email')->nullable();
            $table->boolean('is_default_org')->default(false);
            $table->string('language_code')->nullable();
            $table->tinyInteger('fiscal_year_start_month')->default(0);
            $table->date('account_created_date')->nullable();
            $table->string('time_zone')->nullable();
            $table->boolean('is_org_active');
            $table->string('currency_id')->nullable();
            $table->string('currency_code')->nullable();
            $table->string('currency_symbol')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
