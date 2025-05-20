<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CoreObjectiveItem;

class CoreObjectiveItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CoreObjectiveItem::truncate(); // Clear existing items before seeding

        $objectives = [
            [
                'order' => 1,
                'title' => 'Strengthening Mutual Support',
                'content' => "Abarinzi Family Ministry prioritizes the welfare of its members, ensuring that everyone within the community has access to both spiritual and material support. This support system is crucial for the wellbeing of each member, as it fosters unity and trust within our community. Members in need are assisted through contributions from the ministry, emphasizing the value of collective responsibility and compassion."
            ],
            [
                'order' => 2,
                'title' => 'Education Support Initiatives',
                'content' => "Education is a powerful tool for change, and we are dedicated to making it accessible to underprivileged children. By providing school fees for vulnerable students, we help remove financial barriers that may otherwise hinder their education. We believe that educating the youth benefits both individuals and the larger community by laying a foundation for self-sufficiency and empowerment."
            ],
            [
                'order' => 3,
                'title' => 'Healthcare Assistance',
                'content' => "Health and wellness are central to our mission. To alleviate the financial burden of healthcare, we provide mutual health insurance coverage for our members, ensuring that they have access to essential medical services. Additionally, our members regularly visit hospitals to support and pray with patients, fostering emotional and spiritual comfort for those facing health challenges."
            ],
            [
                'order' => 4,
                'title' => 'Educational Institution Development',
                'content' => "We recognize that sustainable change is achieved through long-term investments in education. With this in mind, we plan to establish a series of private schools, starting from nursery and extending up to university level, as resources allow. Our goal is to provide quality education grounded in our values and tailored to meet the needs of the community. Each educational institution will be developed with a commitment to high academic standards, moral integrity, and inclusive practices."
            ]
        ];

        foreach ($objectives as $objective) {
            CoreObjectiveItem::create($objective);
        }
    }
}