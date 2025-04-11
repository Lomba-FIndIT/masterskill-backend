<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CourseManagementTest extends TestCase
{
    use RefreshDatabase;

    /*
        - can create course x
        - only admin can create course x
        - can get all courses x
        - can get course by id x
        - can update course
        - only admin can update course
        - can delete course
        - only admin can delete course
        - student can attach course
        - only student can attach course
        - student can cancel course
        - only student can cancel course
    */
    public function dummy_user($name = 'admin', $role_id = 1): User
    {
        return User::factory()->create([
            'name' => $name,
            'role_id' => $role_id
        ]);
    }

    public function dummy_course($instructor_id, $course_name = 'test_course', $category_id = 1): Course
    {
        return Course::factory()->create([
            'course_name' => $course_name,
            'category_id' => $category_id,
            'instructor_id' => $instructor_id
        ]);
    }

    public function test_can_create_course(): void
    {
        $admin = $this->dummy_user();

        $instructor = $this->dummy_user('instructor', 2);

        $response = $this->actingAs($admin)->postJson('api/courses', [
            'course_name' => 'test course',
            'category_id' => 1,
            'instructor_id' => $instructor->id
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'course_name',
                'category_id',
                'instructor_id'
            ]);
        $this->assertDatabaseHas('courses', [
            'course_name' => 'test course',
            'category_id' => 1,
            'instructor_id' => $instructor->id
        ]);
    }

    public function test_only_admin_can_create_course(): void
    {
        $student = $this->dummy_user('student', 4);

        $instructor = $this->dummy_user('instructor', 2);

        $response = $this->actingAs($student)->postJson('api/courses', [
            'course_name' => 'test course',
            'category_id' => 1,
            'instructor_id' => $instructor->id
        ]);

        $response->assertStatus(403);
    }

    public function test_can_get_all_courses(): void
    {
        $student = $this->dummy_user('student', 4);

        $response = $this->actingAs($student)->get('api/courses');

        $response->assertJsonCount(10);
    }

    public function test_can_get_course_by_id(): void
    {
        $student = $this->dummy_user('student', 4);

        $instructor = $this->dummy_user('instructor', 2);

        $course = $this->dummy_course($instructor->id);

        $response = $this->actingAs($student)->get('api/courses/' . $course->id);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
            'course_name',
            'category_id',
            'instructor_id'
        ]);
    }

    public function test_can_update_course(): void
    {
        $admin = $this->dummy_user();

        $instructor = $this->dummy_user('instructor', 2);

        $course = $this->dummy_course($instructor->id);

        $response = $this->actingAs($admin)->putJson('api/courses/' . $course->id, [
            'course_name' => $course->course_name,
            'category_id' => 2,
            'instructor_id' => $instructor->id
        ]);

        $response->assertStatus(200);
        $this
            ->assertDatabaseHas('courses', [
                'id' => $course->id,
                'category_id' => 2
            ])
            ->assertDatabaseMissing('courses', [
                'id' => $course->id,
                'category_id' => 1
            ]);
    }
}
