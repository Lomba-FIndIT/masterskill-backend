<?php

namespace Database\Seeders;

use App\Models\Salary;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ranges = [
            [
                'min' => 1000000,
                'max' => 3000000
            ],
            [
                'min' => 3000000,
                'max' => 7000000
            ],
            [
                'min' => 7000000,
                'max' => 10000000
            ]
        ];

        foreach ($ranges as $range) {
            Salary::create([
                'min' => $range['min'],
                'max' => $range['max']
            ]);
        }
    }
}
