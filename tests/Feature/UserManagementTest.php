<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /*
        - admin can create instructor x
        - admin can create hrd x
        - only admin can create x
        - can get all users x
        - only admin can get all users x
        - can get user by  x
        - only admin and it user can see their profiles x
        - can update profile x
        - other user cannot update other user profile x
        - can delete user by id x
        - only admin and it user can delete their profiles x
        - login user can get their profile x
        - hrd and student can get all their cv review shcedule
        - student can get all their courses
        - can get all joined webinars
        - student can get all their payments
    */
    public function dummy_user($name = 'admin', $role_id = 1): User
    {
        return User::factory()->create([
            'name' => $name,
            'role_id' => $role_id
        ]);
    }

    public function test_can_create_instructor_hrd(): void
    {
        $admin = $this->dummy_user();

        $response1 = $this->actingAs($admin)->postJson('api/users', [
            'name' => 'instructor',
            'email' => fake()->safeEmail(),
            'password' => 'password',
            'password_confirmation' => 'password',
            'phone_number' => fake()->phoneNumber(),
            'role_id' => 2
        ]);

        $response2 = $this->actingAs($admin)->postJson('api/users', [
            'name' => 'hrd',
            'email' => fake()->safeEmail(),
            'password' => 'password',
            'password_confirmation' => 'password',
            'phone_number' => fake()->phoneNumber(),
            'role_id' => 3
        ]);

        $response1
            ->assertStatus(201)
            ->assertJsonStructure([
                'name',
                'email',
                'phone_number',
                'role_id'
            ]);
        $response2
            ->assertStatus(201)
            ->assertJsonStructure([
                'name',
                'email',
                'phone_number',
                'role_id'
            ]);
        $this
            ->assertDatabaseHas('users', [
                'name' => 'instructor',
                'role_id' => 2
            ])
            ->assertDatabaseHas('users', [
                'name' => 'hrd',
                'role_id' => 3
            ]);
    }

    public function test_only_admin_can_create_instructor_hrd(): void
    {
        $student = $this->dummy_user('student', 4);

        $response = $this->actingAs($student)->postJson('api/users', [
            'name' => 'instructor',
            'email' => fake()->safeEmail(),
            'password' => 'password',
            'password_confirmation' => 'password',
            'phone_number' => fake()->phoneNumber(),
            'role_id' => 2
        ]);

        $response->assertStatus(403);
    }

    public function test_can_get_all_users(): void
    {
        $admin = $this->dummy_user();

        $response = $this->actingAs($admin)->get('api/users');

        $response
            ->assertStatus(200)
            ->assertJsonCount(5);
    }

    public function test_only_admin_can_get_all_users(): void
    {
        $student = $this->dummy_user('student', 4);

        $response = $this->actingAs($student)->get('api/users');

        $response->assertStatus(403);
    }

    public function test_can_get_user_by_id(): void
    {
        $admin = $this->dummy_user();

        $user = $this->dummy_user('user', 4);

        $response1 = $this->actingAs($admin)->get('api/users/' . $user->id);
        $response2 = $this->actingAs($user)->get('api/users/' . $user->id);

        $response1
            ->assertStatus(200)
            ->assertJson([
                'id' => $user->id
            ]);
        $response2
            ->assertStatus(200)
            ->assertJson([
                'id' => $user->id
            ]);
    }

    public function test_only_admin_and_it_user_can_see_their_profiles(): void
    {
        $user1 = $this->dummy_user('user1', 4);
        $user2 = $this->dummy_user('user2', 4);

        $response = $this->actingAs($user2)->get('api/users/' . $user1->id);

        $response->assertStatus(403);
    }

    public function test_can_update_profiles(): void
    {
        $user = $this->dummy_user('user', 4);
        
        $img = UploadedFile::fake()->image('user.jpg');
        
        $address = fake()->address();
        
        $response = $this->actingAs($user)->put('api/users/' . $user->id, [
            'name' => $user->name,
            'email' => $user->email,
            'address' => $address,
            'phone_number' => $user->phone_number,
            'role_id' => $user->role_id,
            'img' => $img
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'img_url'
            ]);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'address' => $address
        ]);
    }

    public function test_other_user_cannot_update_other_user_profile(): void
    {
        $user1 = $this->dummy_user('user1', 4);
        $user2 = $this->dummy_user('user2', 4);

        $img = UploadedFile::fake()->image('user.jpg');
        
        $address = fake()->address();
        
        $response = $this->actingAs($user2)->put('api/users/' . $user1->id, [
            'name' => $user1->name,
            'email' => $user1->email,
            'address' => $address,
            'phone_number' => $user1->phone_number,
            'role_id' => $user1->role_id,
            'img' => $img
        ]);

        $response->assertStatus(403);
    }

    public function test_user_can_delete_their_profile(): void
    {
        $user = $this->dummy_user('user', 4);

        $response = $this->actingAs($user)->delete('api/users/' . $user->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }

    public function test_only_admin_and_it_user_can_delete_their_profile(): void
    {
        $admin = $this->dummy_user();
        $user = $this->dummy_user('user', 4);
        $user1 = $this->dummy_user('user1', 4);
        $user2 = $this->dummy_user('user2', 4);

        $response1 = $this->actingAs($admin)->delete('api/users/' . $user->id);
        $response2 = $this->actingAs($user2)->delete('api/users/' . $user1->id);

        $response1->assertStatus(204);
        $response2->assertStatus(403);
        $this
            ->assertDatabaseHas('users', [
                'id' => $user1->id
            ])
            ->assertDatabaseMissing('users', [
                'id' => $user->id
            ]);
    }

    public function test_login_user_can_get_their_profiles(): void
    {
        $user = $this->dummy_user('user', 4);

        $response = $this->actingAs($user)->get('api/user');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'role_id',
                'address',
                'phone_number',
                'img_url'
            ]);
    }

    // public function test_hrd_student_can_get_all_their_cv_review_schedules(): void
    // {

    // }

    public function test_student_can_get_all_their_courses(): void
    {
        $student = $this->dummy_user('student', 4);

        $course1 = Course::find(1);
        $course2 = Course::find(2);

        $this->actingAs($student)->get('api/courses/' . $course1->id . '/join');
        $this->actingAs($student)->get('api/courses/' . $course2->id . '/join');

        $response = $this->actingAs($student)->get('api/users/' . $student->id . '/courses');;
        $response
            ->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'course_name',
                    'category',
                    'instructor'
                ]
            ]);
    }

    // public function test_can_get_all_joined_webinars(): void
    // {

    // }

    // public function test_student_can_get_all_their_payments(): void
    // {

    // }
}
