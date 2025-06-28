<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount_requested',
        'amount_approved',
        'purpose',
        'interest_rate', // This is now monthly rate, e.g., 6.00 for 6% per month
        'term_months',
        'status',
        'application_date',
        'approval_date',
        'disbursement_date',
        'first_payment_due_date',
        'final_due_date',
        'admin_notes',
        'approved_by_user_id',
        'total_repayment_calculated', // New database column
    ];

    protected $casts = [
        'amount_requested' => 'decimal:2',
        'amount_approved' => 'decimal:2',
        'interest_rate' => 'decimal:2', // Monthly rate
        'total_repayment_calculated' => 'decimal:2', // Cast for display
        'application_date' => 'date',
        'approval_date' => 'date',
        'disbursement_date' => 'date',
        'first_payment_due_date' => 'date',
        'final_due_date' => 'date',
    ];

    // No longer need to append if it's a DB column, but accessor is still useful for display
    // protected $appends = ['display_total_repayment'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    /**
     * Calculates the total simple interest based on monthly rate and term.
     */
    public function calculateSimpleInterest(): ?float
    {
        $principal = $this->amount_approved ?? $this->amount_requested;
        $monthlyInterestRate = $this->interest_rate; // e.g., 6.00 for 6% per month
        $termMonths = $this->term_months;

        if (is_null($principal) || is_null($monthlyInterestRate) || is_null($termMonths) || $termMonths == 0) {
            return null;
        }

        $rateDecimal = $monthlyInterestRate / 100; // e.g., 6% -> 0.06
        $totalInterest = $principal * $rateDecimal * $termMonths; // P * R (monthly) * T (months)

        return round($totalInterest, 2);
    }

    /**
     * Calculates and sets the total repayment amount.
     * Call this method before saving when terms are finalized.
     */
    public function calculateAndSetTotalRepayment(): void
    {
        $principal = $this->amount_approved ?? $this->amount_requested;
        $totalInterest = $this->calculateSimpleInterest();

        if (!is_null($principal) && !is_null($totalInterest)) {
            $this->total_repayment_calculated = round($principal + $totalInterest, 2);
        } else {
            $this->total_repayment_calculated = null;
        }
    }

    /**
     * Accessor to display the total repayment amount.
     * Prefers the stored calculated value, but can compute for display if not set.
     */
    public function getDisplayTotalRepaymentAttribute(): ?float
    {
        if (!is_null($this->total_repayment_calculated)) {
            return (float) $this->total_repayment_calculated;
        }

        // If not stored, calculate it for display purposes (e.g., for a pending loan preview)
        $principal = $this->amount_approved ?? $this->amount_requested;
        $totalInterest = $this->calculateSimpleInterest();

        if (!is_null($principal) && !is_null($totalInterest)) {
            return round($principal + $totalInterest, 2);
        }
        return null;
    }
}