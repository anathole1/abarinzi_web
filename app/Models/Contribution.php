<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
      protected $fillable = [
        'user_id',
        'type',
        'amount',
        'payment_method',
        'transaction_id',
        'description',
        'status',
        'approved_by',
        'payment_date',
        'category',
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
        return $this->belongsTo(User::class, 'approved_by');
    }
}