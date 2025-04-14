<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WebinarManagementTest extends TestCase
{
    public function dummy_user($name = 'admin', $role_id = 1): User
    {
        return User::factory()->create([
            'name' => $name,
            'role_id' => $role_id
        ]);
    }

    public function test_can_get_all_webinars(): void
    {
        $user = $this->dummy_user();

        $response = $this->actingAs($user)->get('api/webinars');

        $response
            ->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'title',
                    'start_date',
                    'end_date',
                    'webinar_link',
                    'speakers'
                ]
            ]);
    }

    public function test_can_get_webinar_by_id(): void
    {
        $user = $this->dummy_user();

        $response = $this->actingAs($user)->get('api/webinars/1');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'title',
                'start_date',
                'end_date',
                'webinar_link',
                'speakers'
            ]);
    }
}
