<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schedules = [
            [
                'start' => '2025-04-20 09:00:00',
                'end' => '2025-04-20 11:00:00'
            ],
            [
                'start' => '2025-05-20 09:00:00',
                'end' => '2025-05-20 11:00:00'
            ],
            [
                'start' => '2025-04-21 09:00:00',
                'end' => '2025-04-21 11:00:00'
            ],
        ];

        foreach ($schedules as $schedule) {
            Schedule::create([
                'start' => $schedule['start'],
                'end' => $schedule['end']
            ]);
        }
    }
}
