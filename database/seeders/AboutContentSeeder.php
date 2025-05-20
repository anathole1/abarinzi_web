<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AboutContent;

class AboutContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AboutContent::updateOrCreate(['id' => 1], [
            'page_main_title' => 'ABARINZI FAMILY',
            'page_main_subtitle' => 'Project Expansion and Strategic Plan', // Or the more descriptive one

            'intro_title' => 'Introduction',
            'intro_content' => "ABARINZI FAMILY were established in 2008 by a group of secondary school students united by a shared commitment to faith and community. Initially formed as a prayer group, Abarinzi Family Ministry has since evolved into a multi-dimensional organization that aims to address the social, educational, and healthcare needs of its members and the broader community. Rooted in our shared values of compassion, service, and faith, we envision a future where every member and beneficiary of Abarinzi Family Ministry experiences holistic support and growth.",

            'mission_title' => 'Mission Statement',
            'mission_summary' => "To uplift individuals and communities by fostering solidarity, providing essential services to vulnerable populations, and working towards sustainable development.",
            'mission_content' => "Our mission extends beyond spiritual enrichment to include tangible actions that uplift individuals and communities in need. We are committed to fostering solidarity among our members, providing essential services to vulnerable populations, and working towards the sustainable development of educational and healthcare resources.",

            'core_objectives_section_title' => 'Core Objectives and Activities',
            'vision_section_title' => 'Vision for the Future',
            'vision_section_intro_content' => "Our vision for Abarinzi Family Ministry is to create a legacy of lasting impact, one that nurtures community growth and empowers each member and beneficiary to thrive. Our ambitions are rooted in both immediate and long-term goals, and we have a phased plan that balances our current resources with our commitment to sustainable expansion.",

            'concluding_statement' => "Each of these goals reflects our deep commitment to building a strong, compassionate, and resilient community. Through education, healthcare, and a lasting connection to our founding school, we believe Guardian Family Ministry will become a powerful force for positive change, empowering individuals to thrive and serve others.\n\nTogether, through every step we take and every project we undertake, we aim to build a future that upholds our core values and brings tangible benefits to both our members and the broader society.", // Added \n\n for paragraph break

            // Optional: Join Card & Stats (from previous defaults or new ones)
            'join_card_title' => 'Become an Active Member',
            'join_card_text' => 'Join our vibrant community today to unlock exclusive benefits and connect with fellow members.',
            'join_card_button_text' => 'Register Now',
            'stats_items' => [ // Keep as array, model will cast
                ['value' => '500+', 'label' => 'Active Members'],
                ['value' => '10+', 'label' => 'Community Projects Initiated'],
                ['value' => '15+', 'label' => 'Years of Service'],
            ]
        ]);
    }
}