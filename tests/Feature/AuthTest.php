<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function dummy_admin(): User
    {
        return User::factory()->create([
            'email' => 'admin@email.com',
            'role_id' => 1
        ]);
    }

    public function test_can_register_as_admin()
    {
        $address = fake()->address();

        $response = $this->postJson('api/register', [
            'name' => 'admin_test',
            'email' => 'admin@email.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role_id' => 1,
            'address' => $address,
            'phone_number' => fake()->phoneNumber()
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'email' => 'admin@email.com',
            'role_id' => 1,
            'address' => $address
        ]);
    }

    public function test_can_register_as_student()
    {
        $address = fake()->address();

        $response = $this->postJson('api/register', [
            'name' => 'student_test',
            'email' => 'student@email.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role_id' => 4,
            'address' => $address,
            'phone_number' => fake()->phoneNumber()
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'email' => 'student@email.com',
            'role_id' => 4,
            'address' => $address
        ]);
    }

    public function test_instructor_cannot_register()
    {
        $response = $this->postJson('api/register', [
            'name' => 'instructor_test',
            'email' => 'instructor@email.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role_id' => 2,
            'phone_number' => fake()->phoneNumber()
        ]);

        $response->assertStatus(400);
        $this->assertDatabaseMissing('users', [
            'name' => 'instructor_test',
            'role_id' => 2
        ]);
    }

    public function test_hrd_cannot_register()
    {
        $response = $this->postJson('api/register', [
            'name' => 'hrd_test',
            'email' => 'hrd@email.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role_id' => 3,
            'phone_number' => fake()->phoneNumber()
        ]);

        $response->assertStatus(400);
        $this->assertDatabaseMissing('users', [
            'name' => 'hrd_test',
            'role_id' => 3
        ]);
    }

    public function test_can_login()
    {
        $admin = $this->dummy_admin();

        $response = $this->postJson('api/login', [
            'email' => $admin->email,
            'password' => 'password'
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    public function test_can_logout()
    {
        $admin = $this->dummy_admin();

        $response = $this->actingAs($admin)->get('api/logout');

        $response->assertStatus(204);
    }
}
