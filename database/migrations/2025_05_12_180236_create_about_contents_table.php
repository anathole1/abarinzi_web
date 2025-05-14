<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('about_contents', function (Blueprint $table) {
            $table->id();
            $table->string('main_title')->default('About Us');
            $table->text('main_subtitle')->nullable();
            $table->string('story_title')->default('Our Story');
            $table->text('story_paragraph1')->nullable();
            $table->text('story_paragraph2')->nullable();
            // Store value props perhaps as JSON, or separate columns if fixed
            // JSON approach:
            $table->json('value_cards')->nullable();
            // Example JSON structure:
            // [
            //   {"icon": "connection_icon_svg", "title": "Connection", "description": "..."},
            //   {"icon": "support_icon_svg", "title": "Support", "description": "..."},
            //   {"icon": "growth_icon_svg", "title": "Growth", "description": "..."}
            // ]
            $table->string('join_title')->default('Become a Member');
            $table->text('join_text')->nullable();
            $table->string('join_button_text')->default('Register Now');
            // Store stats as JSON
            $table->json('stats')->nullable();
             // Example JSON structure:
             // [
             //   {"value": "100+", "label": "Successful Events"},
             //   {"value": "500+", "label": "Active Members"},
             //   ...
             // ]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_contents');
    }
};