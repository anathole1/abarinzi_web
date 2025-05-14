<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// REMOVE: use Illuminate\Support\Facades\Storage; // No longer needed for this accessor if not using Storage facade

class HeroSlide extends Model {
    use HasFactory;
    protected $fillable = [
        'title', 'description', 'image_path', 'cta1_text', 'cta1_link',
        'cta2_text', 'cta2_link', 'order', 'is_active',
    ];
    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Accessor to get the full public URL for the image
    public function getImageUrlAttribute() {
        if ($this->image_path && file_exists(public_path($this->image_path))) {
            // Use Laravel's asset() helper which correctly points to the public directory
            return asset($this->image_path);
        }
        // Return a default placeholder image if needed, also in public directory
        return asset('images/placeholder-slide.jpg'); // e.g., public/images/placeholder-slide.jpg
    }
}