<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// REMOVE: use Illuminate\Support\Facades\Storage; // No longer needed for this specific accessor

class MemberProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'accountNo',
        'email',
        'phone_number',
        'national_id',
        'year_left_efotec',
        'current_location',
        'dateJoined',
        'photoUrl', // Stores path like "uploads/member_photos/image.jpg"
        'member_category_id',
        'status',
    ];

    protected $casts = [
        'dateJoined' => 'date',
    ];

    /**
     * Accessor to get the full public URL for the photo.
     * Assumes photoUrl stores a path relative to the public directory.
     */
    public function getFullPhotoUrlAttribute(): ?string
    {
        if ($this->photoUrl && file_exists(public_path($this->photoUrl))) {
            // asset() helper correctly generates URLs for files in the public directory
            return asset($this->photoUrl);
        }
        // Default placeholder image also in public/images/
        return asset('images/default-avatar.png');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function memberCategory(): BelongsTo
    {
        return $this->belongsTo(MemberCategory::class);
    }
}