<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WorkManagementTest extends TestCase
{
    public function dummy_user($name = 'admin', $role_id = 1): User
    {
        return User::factory()->create([
            'name' => $name,
            'role_id' => $role_id
        ]);
    }

    public function test_can_get_all_works(): void
    {
        $user = $this->dummy_user('student', 4);

        $response = $this->actingAs($user)->get('api/works');

        $response
            ->assertStatus(200)
            ->assertJsonCount(20)
            ->assertJsonStructure([
                '*' => [
                    'company_name',
                    'company_address',
                    'job_description',
                    'experience',
                    'category',
                    'min_salary',
                    'max_salary',
                    'hrd_email',
                    'contact'
                ]
            ]);
    }

    public function test_can_get_work_by_id(): void
    {
        $user = $this->dummy_user('student', 4);

        $response = $this->actingAs($user)->get('api/works/1');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'company_name',
                'company_address',
                'job_description',
                'experience',
                'category',
                'min_salary',
                'max_salary',
                'hrd_email',
                'contact'
            ]);
    }

    public function test_can_get_all_salaries(): void
    {
        $user = $this->dummy_user('student', 4);

        $response = $this->actingAs($user)->get('api/salaries');

        $response
            ->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => [
                    'min',
                    'max'
                ]
            ]);
    }

    public function test_can_get_all_experiences(): void
    {
        $user = $this->dummy_user('student', 4);

        $response = $this->actingAs($user)->get('api/experiences');

        $response
            ->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => [
                    'experience'
                ]
            ]);
    }
}
