<?php

namespace Database\Seeders;

use App\Models\Speaker;
use App\Models\Webinar;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WebinarSeeder extends Seeder
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

        $speaker1 = Speaker::find(1);
        $speaker2 = Speaker::find(2);

        foreach ($schedules as $schedule) {
            $webinar = Webinar::factory()->create([
                'start_date' => Carbon::parse($schedule['start']),
                'end_date' => Carbon::parse($schedule['end'])
            ]);

            $webinar->speakers()->attach($speaker1->id);
            $webinar->speakers()->attach($speaker2->id);
        }
    }
}
