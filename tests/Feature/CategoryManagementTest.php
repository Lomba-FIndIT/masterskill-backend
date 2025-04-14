<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function dummy_user($name = 'admin', $role_id = 1): User
    {
        return User::factory()->create([
            'name' => $name,
            'role_id' => $role_id
        ]);
    }

    public function test_can_get_all_categories(): void
    {
        $response = $this->get('api/categories');

        $response
            ->assertStatus(200)
            ->assertJsonCount(4)
            ->assertJsonStructure([
                '*' => [
                    'category_name'
                ]
            ]);
    }
}
