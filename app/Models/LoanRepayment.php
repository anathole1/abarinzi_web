<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class LoanRepayment extends Model {
    use HasFactory;
    protected $fillable = [
        'loan_id', 'user_id', 'amount_paid', 'payment_date', 'payment_method',
        'transaction_id', 'status', 'notes', 'confirmed_by_user_id', 'confirmation_date',
    ];
    protected $casts = ['payment_date' => 'date', 'confirmation_date' => 'datetime', 'amount_paid' => 'decimal:2'];
    public function loan() { return $this->belongsTo(Loan::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function confirmer() { return $this->belongsTo(User::class, 'confirmed_by_user_id'); }
}
