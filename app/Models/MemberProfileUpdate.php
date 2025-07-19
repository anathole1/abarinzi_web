<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberProfileUpdate extends Model {
    use HasFactory;
    protected $fillable = [
        'user_id',
        'member_profile_id',
        'updated_data',
        'status',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
    ];
    protected $casts = [
        'updated_data' => 'array', // Automatically cast JSON to/from array
        'reviewed_at' => 'datetime',
    ];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function memberProfile(): BelongsTo { return $this->belongsTo(MemberProfile::class); }
    public function reviewer(): BelongsTo { return $this->belongsTo(User::class, 'reviewed_by'); }
}