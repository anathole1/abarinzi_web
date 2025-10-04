<?php

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    public function run(): void
    {
        // Using updateOrCreate to prevent duplicates on re-seeding
        $offices = [
            ['display_order' => 1, 'name' => 'Director General', 'code' => 'DG'],
            ['display_order' => 2, 'name' => 'Deputy Director General', 'code' => 'DDG'],
            ['display_order' => 3, 'name' => 'Secretary General', 'code' => 'SG'],
            ['display_order' => 4, 'name' => 'Financial Operation and Administrator Officer', 'code' => 'FOAO'],
            ['display_order' => 5, 'name' => 'Information and Technology Director', 'code' => 'ITD'], // Corrected code
            ['display_order' => 6, 'name' => 'Assistant Information and Technology Director', 'code' => 'AITD'], // Corrected code
            ['display_order' => 7, 'name' => 'Spiritual and Community outreach Officer', 'code' => 'SCO'],
            ['display_order' => 8, 'name' => 'Banking Sector Director', 'code' => 'BSD'],
            ['display_order' => 9, 'name' => 'Education Sector Director', 'code' => 'ESD'],
            ['display_order' => 10, 'name' => 'Health Sector Director', 'code' => 'HSD'],
            ['display_order' => 11, 'name' => 'Auditing Officer', 'code' => 'AO'],
        ];

        foreach ($offices as $office) {
            Office::updateOrCreate(['name' => $office['name']], $office);
        }
    }
}