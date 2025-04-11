<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // admin
        for ($i = 0; $i < 1; $i++) {
            User::factory()->create([
                'role_id' => 1
            ]);
        }
        // instructor
        for ($i = 0; $i < 1; $i++) {
            User::factory()->create([
                'role_id' => 2
            ]);
        }
        // hrd
        for ($i = 0; $i < 1; $i++) {
            User::factory()->create([
                'role_id' => 3
            ]);
        }
        // student
        for ($i = 0; $i < 1; $i++) {
            User::factory()->create([
                'role_id' => 4
            ]);
        }
    }
}
