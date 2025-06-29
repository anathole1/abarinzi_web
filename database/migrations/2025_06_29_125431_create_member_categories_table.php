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
        Schema::create('member_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., "Bronze", "Silver", "Gold"
            $table->decimal('monthly_contribution', 10, 2);
            $table->decimal('annual_contribution', 12, 2); // Calculated as monthly * 12 or set directly
            $table->decimal('percentage_of_loan_allowed', 5, 2)->comment('e.g., 50.00 for 50% of savings/contributions');
            $table->decimal('monthly_interest_rate_loan', 5, 2)->comment('Monthly interest rate for loans in this category, e.g., 1.5 for 1.5%');
            $table->decimal('social_monthly_contribution', 10, 2)->nullable();
            $table->text('description')->nullable(); // Optional description of benefits
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_categories');
    }
};
