<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\AboutContent;

class AboutContentSeeder extends Seeder {
    public function run(): void {
        AboutContent::updateOrCreate(['id' => 1], [
            'main_title' => 'About EFOTEC Alumni Association',
            'main_subtitle' => 'A community dedicated to fostering connections, supporting growth, and celebrating the achievements of EFOTEC graduates.',
            'story_title' => 'Our Journey & Purpose',
            'story_paragraph1' => 'Established by a passionate group of alumni, the EFOTEC Alumni Association aims to build a lasting bridge between graduates, current students, and the institution itself. We believe in the power of our collective network.',
            'story_paragraph2' => 'Through various programs, events, and initiatives, we strive to provide valuable resources, facilitate professional development, and create opportunities for meaningful engagement. Our commitment is to the success and well-being of every member.',
            'value_cards' => json_encode([
                ['icon_svg_path' => 'M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-4.5A3.375 3.375 0 0 0 12.75 9.75H11.25A3.375 3.375 0 0 0 7.5 13.125V18.75m9 0h-9', 'title' => 'Connect', 'description' => 'Build and maintain strong professional and personal relationships within the EFOTEC network.'],
                ['icon_svg_path' => 'M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m8.198 0a24.716 24.716 0 0 0-7.734 0m7.734 0a24.733 24.733 0 0 1 3.741 0M6 18.719L6 7.25a6 6 0 0 1 6-6s6 2.686 6 6v11.47Z', 'title' => 'Empower', 'description' => 'Access resources, mentorship, and opportunities that foster personal and career growth.'],
                ['icon_svg_path' => 'M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941', 'title' => 'Contribute', 'description' => 'Give back to the EFOTEC community and support the next generation of leaders and innovators.'],
            ]),
            'join_title' => 'Become an Active Member',
            'join_text' => 'Join our vibrant community today to unlock exclusive benefits and connect with fellow alumni.',
            'join_button_text' => 'Register Now',
            'stats' => json_encode([
                ['value' => '500+', 'label' => 'Registered Alumni'],
                ['value' => '50+', 'label' => 'Annual Events & Meetups'],
                ['value' => '10+', 'label' => 'Mentorship Programs'],
                ['value' => '90%', 'label' => 'Member Satisfaction'],
            ]),
        ]);
    }
}