<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MemberCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'monthly_contribution',
        'annual_contribution',
        'percentage_of_loan_allowed',
        'monthly_interest_rate_loan',
        'social_monthly_contribution',
        'description',
        'is_active',
    ];

    protected $casts = [
        'monthly_contribution' => 'decimal:2',
        'annual_contribution' => 'decimal:2',
        'percentage_of_loan_allowed' => 'decimal:2',
        'monthly_interest_rate_loan' => 'decimal:2',
        'social_monthly_contribution' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function memberProfiles(): HasMany
    {
        return $this->hasMany(MemberProfile::class);
    }

    // Optional: Accessor if annual is always calculated from monthly
    // public function getCalculatedAnnualContributionAttribute(): float
    // {
    //     return $this->monthly_contribution * 12;
    // }
}