<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type', // monthly_membership, social_contribution, other
        'amount',
        'payment_method',
        'transaction_id',
        'description',
        'status',
        'approved_by', // Note: This was 'approved_by_user_id' in Loan model, ensure consistency or map
        'payment_date',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        // Assuming 'approved_by' stores a user_id
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Relationship to MemberProfile (needed to get member's category)
    // This assumes a User has one MemberProfile.
    public function memberProfile()
    {
        return $this->hasOneThrough(MemberProfile::class, User::class, 'id', 'user_id', 'user_id', 'id');
        // Or, more simply, if you always fetch contributions via user: $contribution->user->memberProfile
    }
}