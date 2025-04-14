<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this
            ->call(RoleSeeder::class)
            ->call(CategorySeeder::class)
            ->call(UserSeeder::class)
            ->call(CourseSeeder::class)
            ->call(SalarySeeder::class)
            ->call(ExperienceSeeder::class)
            ->call(WorkSeeder::class)
            ->call(ScheduleSeeder::class)
            ->call(SpeakerSeeder::class)
            ->call(WebinarSeeder::class);
    }
}
