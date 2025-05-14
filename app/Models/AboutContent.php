<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutContent extends Model
{
    use HasFactory;
     protected $fillable = [
        'main_title', 'main_subtitle', 'story_title', 'story_paragraph1',
        'story_paragraph2', 'value_cards', 'join_title', 'join_text',
        'join_button_text', 'stats',
    ];
    protected $casts = [
        'value_cards' => 'array', // Cast JSON column to PHP array
        'stats' => 'array',       // Cast JSON column to PHP array
    ];
}