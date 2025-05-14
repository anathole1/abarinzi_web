<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberProfile extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone_number',
        'national_id',
        'year_left_efotec',
        'current_location',
        'membership_category',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper for membership category amounts
    public static function getMembershipCategoryAmounts(): array
    {
        return [
            'bronze' => 2500,
            'silver' => 5000,
            'gold' => 7500,
        ];
    }

    public function getMembershipAmountAttribute(): int
    {
        $amounts = self::getMembershipCategoryAmounts();
        return $amounts[$this->membership_category] ?? 0;
    }
}