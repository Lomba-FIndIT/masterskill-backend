<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CourseManagementTest extends TestCase
{
    use RefreshDatabase;

    /*
        - can create course x
        - only admin can create course x
        - can get all courses x
        - can get course by id x
        - can update course x
        - only admin can update course x
        - can delete course x
        - only admin can delete course x
        - student can attach course x
        - only student can attach course x
        - student can cancel course x
        - only student can cancel course x
        - can get all students by course id x
        - user can give course ratings
        - can get all videos by course id
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

        $img = UploadedFile::fake()->image('course.jpg');

        $instructor = $this->dummy_user('instructor', 2);

        $response = $this->actingAs($admin)->postJson('api/courses', [
            'course_name' => 'test course',
            'category_id' => 1,
            'instructor_id' => $instructor->id,
            'price' => 20000,
            'img' => $img
        ]);

        $category = Category::find(1);

        $response
            ->assertStatus(201)
            ->assertJson([
                'course_name' => 'test course',
                'category' => $category->category_name,
                'instructor' => $instructor->name,
                'price' => 20000,
                'total_duration' => 0
            ])
            ->assertJsonStructure([
                'img_url'
            ]);
        $this->assertDatabaseHas('courses', [
            'course_name' => 'test course',
            'category_id' => 1,
            'instructor_id' => $instructor->id,
            'price' => 20000,
            'total_duration' => 0
        ]);
    }

    public function test_only_admin_can_create_course(): void
    {
        $student = $this->dummy_user('student', 4);

        $instructor = $this->dummy_user('instructor', 2);

        $response = $this->actingAs($student)->postJson('api/courses', [
            'course_name' => 'test course',
            'category_id' => 1,
            'instructor_id' => $instructor->id,
            'price' => 10000
        ]);

        $response->assertStatus(403);
    }

    public function test_can_get_all_courses(): void
    {
        $response = $this->get('api/courses');

        $response
            ->assertJsonCount(10)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'course_name',
                    'category',
                    'instructor',
                    'price',
                    'img_url',
                    'total_duration',
                    'ratings'
                ]
            ]);
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
                'id',
                'course_name',
                'category',
                'instructor',
                'price',
                'img_url',
                'total_duration',
                'ratings'
            ]);
    }

    public function test_can_update_course(): void
    {
        $admin = $this->dummy_user();

        $instructor = $this->dummy_user('instructor', 2);

        $course = $this->dummy_course($instructor->id);

        $img = UploadedFile::fake()->image('course.jpg');

        $response = $this->actingAs($admin)->putJson('api/courses/' . $course->id, [
            'course_name' => $course->course_name,
            'category_id' => 2,
            'instructor_id' => $instructor->id,
            'price' => $course->price,
            'img' => $img
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

    public function test_only_admin_can_update_course(): void
    {
        $instructor = $this->dummy_user('instructor', 2);

        $course = $this->dummy_course($instructor->id);

        $response = $this->actingAs($instructor)->putJson('api/courses/' . $course->id, [
            'course_name' => $course->course_name,
            'category_id' => 2,
            'instructor_id' => $instructor->id,
            'price' => $course->price
        ]);

        $response->assertStatus(403);
    }

    public function test_can_delete_course(): void
    {
        $admin = $this->dummy_user();

        $instructor = $this->dummy_user('instructor', 2);

        $course = $this->dummy_course($instructor->id);

        $response = $this->actingAs($admin)->delete('api/courses/' . $course->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('courses', [
            'id' => $course->id
        ]);
    }

    public function test_only_admin_can_delete_course(): void
    {
        $instructor = $this->dummy_user('instructor', 2);

        $course = $this->dummy_course($instructor->id);

        $response = $this->actingAs($instructor)->delete('api/courses/' . $course->id);

        $response->assertStatus(403);
    }

    public function test_student_can_attach_course(): void
    {
        $student = $this->dummy_user('student', 4);

        $instructor = $this->dummy_user('instructor', 2);

        $course = $this->dummy_course($instructor->id);

        $response = $this->actingAs($student)->get('api/courses/' . $course->id . '/join');

        $response->assertStatus(201);
        $this->assertDatabaseHas('course_user', [
            'course_id' => $course->id,
            'user_id' => $student->id
        ]);
    }

    public function test_only_student_can_attach_course(): void
    {
        $instructor = $this->dummy_user('instructor', 2);

        $course = $this->dummy_course($instructor->id);

        $response = $this->actingAs($instructor)->get('api/courses/' . $course->id . '/join');

        $response->assertStatus(403);
    }

    public function test_student_can_cancel_course(): void
    {
        $student = $this->dummy_user('student', 4);

        $instructor = $this->dummy_user('instructor', 2);

        $course = $this->dummy_course($instructor->id);

        $this->actingAs($student)->get('api/courses/' . $course->id . '/join');

        $payment = Payment::find($course->students()->wherePivot('user_id', $student->id)->first()->pivot->payment_id);
        
        $response = $this->actingAs($student)->delete('api/courses/' . $course->id . '/join');

        $response->assertStatus(204);
        $this
            ->assertDatabaseMissing('course_user', [
                'course_id' => $course->id,
                'user_id' => $student->id
            ])
            ->assertDatabaseMissing('payments', [
                'id' => $payment->id
            ]);
    }

    public function test_only_student_can_cancel_course(): void
    {
        $instructor = $this->dummy_user('instructor', 2);

        $course = $this->dummy_course($instructor->id);

        $this->actingAs($instructor)->get('api/courses/' . $course->id . '/join');
        
        $response = $this->actingAs($instructor)->delete('api/courses/' . $course->id . '/join');

        $response->assertStatus(403);
    }

    public function test_can_get_all_students_by_course_id(): void
    {
        $student = $this->dummy_user('student', 4);

        $instructor = $this->dummy_user('instructor', 2);

        $course = $this->dummy_course($instructor->id);
        
        for ($i = 0; $i < 5; $i++) {
            $student_i = $this->dummy_user('student' . $i, 4);
            $this->actingAs($student_i)->get('api/courses/' . $course->id . '/join');
        }

        $response = $this->actingAs($student)->get('api/courses/' . $course->id . '/students');

        $response
            ->assertStatus(200)
            ->assertJsonCount(5);
    }

    public function test_student_can_give_course_ratings(): void
    {
        $student1 = $this->dummy_user('student1', 4);
        $student2 = $this->dummy_user('student2', 4);

        $instructor = $this->dummy_user('instructor', 2);

        $course = $this->dummy_course($instructor->id);

        $this->actingAs($student1)->get('api/courses/' . $course->id . '/join');
        $this->actingAs($student2)->get('api/courses/' . $course->id . '/join');
        
        $response1 = $this->actingAs($student1)->postJson('api/courses/' . $course->id . '/rate', [
            'rate' => 5
        ]);
        $response2 = $this->actingAs($student2)->postJson('api/courses/' . $course->id . '/rate', [
            'rate' => 4
        ]);

        $response1->assertStatus(200);
        $response2->assertStatus(200);
        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'ratings' => 4.5
        ]);
    }

    public function test_cannot_give_ratings_lesser_than_0_or_greater_than_5(): void
    {
        $student = $this->dummy_user('student', 4);

        $instructor = $this->dummy_user('instructor', 2);

        $course = $this->dummy_course($instructor->id);

        $this->actingAs($student)->get('api/courses/' . $course->id . '/join');
        
        $response1 = $this->actingAs($student)->postJson('api/courses/' . $course->id . '/rate', [
            'rate' => -1
        ]);
        $response2 = $this->actingAs($student)->postJson('api/courses/' . $course->id . '/rate', [
            'rate' => 6
        ]);

        $response1->assertStatus(422);
        $response2->assertStatus(422);
    }

    public function test_only_assigned_course_student_can_give_rating_on_it_course(): void
    {
        $student1 = $this->dummy_user('student1', 4);
        $student2 = $this->dummy_user('student2', 4);

        $instructor = $this->dummy_user('instructor', 2);

        $course = $this->dummy_course($instructor->id);

        $this->actingAs($student1)->get('api/courses/' . $course->id . '/join');
        
        $response = $this->actingAs($student2)->postJson('api/courses/' . $course->id . '/rate', [
            'rate' => 5
        ]);

        $response->assertStatus(403);
    }

    public function test_can_get_all_videos_by_course_id(): void
    {
        
    }
}