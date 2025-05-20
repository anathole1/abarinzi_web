<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VisionItem;

class VisionItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VisionItem::truncate(); // Clear existing items

        $visionPoints = [
            [
                'order' => 1,
                'title' => 'Building a Comprehensive Educational System',
                'content' => "We are committed to establishing a series of educational institutions that will provide accessible, high-quality education. Beginning with nursery schools, we plan to progressively expand to primary, secondary, and ultimately university levels as resources allow. Each school will be developed with a strong foundation in academic excellence, moral guidance, and community-oriented values. Through these schools, we aim to prepare students not only academically but also to inspire them with the core values of service, integrity, and leadership that Abarinzi Family Ministry upholds."
            ],
            [
                'order' => 2,
                'title' => 'Expanding Access to Healthcare',
                'content' => "Healthcare remains a central part of our long-term vision. To address the immediate healthcare needs of our members and the broader community, we plan to launch a pharmacy as our first healthcare initiative. This pharmacy will be a stepping stone toward establishing a full-scale hospital in the future, which will provide comprehensive health services and specialized care. Our goal is to create a healthcare facility that is accessible and equipped to address the medical needs of our community, thereby fostering health and wellness for all."
            ],
            [
                'order' => 3,
                'title' => 'Sustaining Our Legacy at EFOTEC',
                'content' => "As our organization originated at EFOTEC, we are committed to preserving and expanding our presence within the school. We envision a sustainable tradition where new members are welcomed annually from EFOTEC, ensuring that Abarinzi Family Ministry continues to grow with each generation. This process will involve actively recruiting and mentoring new members from EFOTEC and providing support and guidance to current students. By maintaining this connection, we honor our roots and ensure that our values and mission are passed down to future generations of students."
            ]
        ];

        foreach ($visionPoints as $point) {
            VisionItem::create($point);
        }
    }
}