<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoreObjectiveItem extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'content', 'order'];
    // public function aboutContent() { return $this->belongsTo(AboutContent::class); } // If using foreign key

}