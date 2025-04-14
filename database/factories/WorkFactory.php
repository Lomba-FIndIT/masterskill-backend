<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Work>
 */
class WorkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_name' => fake()->company(),
            'company_address' => fake()->address(),
            'job_description' => fake()->jobTitle(),
            'experience_id' => fake()->randomElement([1,2,3]),
            'category_id' => fake()->randomElement([1,2,3,4]),
            'salary_id' => fake()->randomElement([1,2,3]),
            'hrd_email' => fake()->companyEmail(),
            'contact' => fake()->phoneNumber()
        ];
    }
}
