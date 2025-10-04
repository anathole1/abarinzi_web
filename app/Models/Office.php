<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Office extends Model
{
   use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'display_order',
        'user_id', // The user assigned to this office
    ];

    /**
     * Get the user currently holding this office.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
