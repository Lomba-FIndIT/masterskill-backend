<?php

namespace Database\Seeders;

use App\Models\Experience;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $experiences = ['< 1 yrs', '1 - 3 yrs', '> 3 yrs'];

        foreach($experiences as $experience) {
            Experience::create([
                'experience' => $experience
            ]);
        }
    }
}
