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
            $table->string('page_main_title')->default('About Abarinzi Family');
            $table->text('page_main_subtitle')->nullable();

            $table->string('intro_title')->default('Introduction');
            $table->text('intro_content')->nullable();

            $table->string('mission_title')->default('Mission Statement');
            $table->text('mission_summary')->nullable(); // Summary for homepage
            $table->text('mission_content')->nullable();   // Full content for details page

            // Titles for the sections whose items are in separate tables
            $table->string('core_objectives_section_title')->default('Core Objectives and Activities');
            $table->string('vision_section_title')->default('Vision for the Future');
            $table->text('vision_section_intro_content')->nullable(); // Intro para for vision on details page

            $table->text('concluding_statement')->nullable(); // This will be on the "Read More" page

            // Optional: Join Card & Stats (if they remain on the main About section on homepage)
            $table->string('join_card_title')->nullable();
            $table->text('join_card_text')->nullable();
            $table->string('join_card_button_text')->nullable();
            $table->json('stats_items')->nullable(); // Keep as JSON if simple key-value

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