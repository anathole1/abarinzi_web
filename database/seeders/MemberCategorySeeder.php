<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MemberCategory;

class MemberCategorySeeder extends Seeder
{
    public function run(): void
    {
        MemberCategory::truncate(); // Optional: clear table before seeding

        MemberCategory::create([
            'name' => 'Bronze',
            'monthly_contribution' => 2500,
            'annual_contribution' => 2500 * 12,
            'percentage_of_loan_allowed' => 50, // Example: 50%
            'monthly_interest_rate_loan' => 6, // Example: 2%
            'social_monthly_contribution' =>250, // Example
            'description' => 'Basic membership with essential benefits.',
            'is_active' => true,
        ]);

        MemberCategory::create([
            'name' => 'Silver',
            'monthly_contribution' => 5000,
            'annual_contribution' => 5000 * 12,
            'percentage_of_loan_allowed' => 75,
            'monthly_interest_rate_loan' => 5,
            'social_monthly_contribution' => 500,
            'description' => 'Enhanced membership with more benefits and higher loan eligibility.',
            'is_active' => true,
        ]);

        MemberCategory::create([
            'name' => 'Gold',
            'monthly_contribution' => 7500,
            'annual_contribution' => 7500 * 12,
            'percentage_of_loan_allowed' => 90,
            'monthly_interest_rate_loan' => 4,
            'social_monthly_contribution' => 750,
            'description' => 'Premium membership with full benefits and best loan terms.',
            'is_active' => true,
        ]);
    }
}