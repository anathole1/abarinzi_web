<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AboutContent extends Model {
    use HasFactory;

    protected $fillable = [
        'page_main_title', 'page_main_subtitle',
        'intro_title', 'intro_content',
        'mission_title', 'mission_summary', 'mission_content',
        'core_objectives_section_title', // Title for the list
        'vision_section_title',          // Title for the list
        'vision_section_intro_content',
        'concluding_statement',
        'join_card_title', 'join_card_text', 'join_card_button_text', 'stats_items',
    ];

    protected $casts = [
        'stats_items' => 'array',
    ];

    public function coreObjectiveItems(): HasMany {
        return $this->hasMany(CoreObjectiveItem::class/*, 'about_content_id'*/)->orderBy('order');
    }

    public function visionItems(): HasMany {
        return $this->hasMany(VisionItem::class/*, 'about_content_id'*/)->orderBy('order');
    }

    public static function getContent(): self {
        // Ensure default values are set for new fields
        return self::firstOrCreate(['id' => 1], [
            'page_main_title' => 'About Abarinzi Family',
            'page_main_subtitle' => 'A community dedicated...',
            'intro_title' => 'Introduction',
            'intro_content' => "ABARINZI FAMILY were established...",
            'mission_title' => 'Mission Statement',
            'mission_summary' => "To uplift individuals...", // Summary for homepage
            'mission_content' => "Our mission extends beyond spiritual enrichment...", // Full for details page
            'core_objectives_section_title' => 'Core Objectives and Activities',
            'vision_section_title' => 'Vision for the Future',
            'vision_section_intro_content' => "Our vision for Abarinzi Family Ministry is to create a legacy...",
            'concluding_statement' => "Each of these goals reflects our deep commitment...",
            // ... other defaults ...
        ]);
    }
}