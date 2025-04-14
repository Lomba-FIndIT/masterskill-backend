<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VideoManagementTest extends TestCase
{
    use RefreshDatabase;
    /*
        - admin can upload video x
        - admin can update video details x
        - admin can delete video
        - admin can get all videos
        - only admin can creaate, update, delete, get all videos
    */
    public function dummy_user($name = 'admin', $role_id = 1): User
    {
        return User::factory()->create([
            'name' => $name,
            'role_id' => $role_id
        ]);
    }

    public function test_can_upload_video(): void
    {
        $admin = $this->dummy_user();

        $video = UploadedFile::fake()->create('test.mp4', 5000);

        $response = $this->actingAs($admin)->post('api/videos', [
            'title' => 'test video',
            'course_id' => 5,
            'description' => 'test description',
            'video' => $video,
            'duration' => 120,
            'free' => true
        ]);
        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'title',
                'course_id',
                'description',
                'video_url',
                'duration',
                'free'
            ]);
        $this->assertDatabaseHas('videos', [
            'id' => $response['id'],
        ]);
    }

    public function test_can_update_video_details(): void
    {
        $admin = $this->dummy_user();

        $video = UploadedFile::fake()->create('test.mp4', 5000);

        $uploadedVideo = $this->actingAs($admin)->post('api/videos', [
            'title' => 'test video',
            'course_id' => 5,
            'description' => 'test description',
            'video' => $video,
            'duration' => 120,
            'free' => true
        ]);

        $response = $this->actingAs($admin)->put('api/videos/' . $uploadedVideo['id'], [
            'title' => 'test video',
            'course_id' => 5,
            'description' => 'new description',
            'video' => $video,
            'duration' => 120,
            'free' => false
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'title',
                'course_id',
                'description',
                'video_url',
                'duration',
                'free'
            ]);
        $this
            ->assertDatabaseHas('videos', [
                'id' => $response['id'],
                'description' => 'new description',
                'free' => false
            ])
            ->assertDatabaseMissing('videos', [
                'id' => $response['id'],
                'video_url' => $uploadedVideo['video_url']
            ]);
    }

    public function test_can_delete_videos(): void
    {
        $admin = $this->dummy_user();

        $video = UploadedFile::fake()->create('test.mp4', 5000);

        $uploadedVideo = $this->actingAs($admin)->post('api/videos', [
            'title' => 'test video',
            'course_id' => 5,
            'description' => 'test description',
            'video' => $video,
            'duration' => 120,
            'free' => true
        ]);

        $response = $this->actingAs($admin)->delete('api/videos/' . $uploadedVideo['id']);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('videos', [
            'id' => $uploadedVideo['id']
        ]);
    }

    public function test_can_get_all_videos(): void
    {
        $admin = $this->dummy_user();

        $video = UploadedFile::fake()->create('test.mp4', 5000);

        $this->actingAs($admin)->post('api/videos', [
            'title' => 'test video',
            'course_id' => 5,
            'description' => 'test description',
            'video' => $video,
            'duration' => 120,
            'free' => true
        ]);

        $response = $this->actingAs($admin)->get('api/videos');
        $response
            ->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'title',
                    'course_id',
                    'description',
                    'video_url',
                    'duration',
                    'free'
                ]
            ]);
    }

    public function test_student_can_get_video_by_id(): void
    {
        $admin = $this->dummy_user();
        $student = $this->dummy_user('student', 4);

        $video = UploadedFile::fake()->create('test.mp4', 5000);

        $uploadedVideo = $this->actingAs($admin)->post('api/videos', [
            'title' => 'test video',
            'course_id' => 5,
            'description' => 'test description',
            'video' => $video,
            'duration' => 120,
            'free' => true
        ]);

        $response = $this->actingAs($student)->get('api/videos/' . $uploadedVideo['id']);
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'title',
                'course_id',
                'description',
                'video_url',
                'duration',
                'free'
            ]);
    }

    public function test_student_can_stream_video_if_paid_or_free(): void
    {
        $admin = $this->dummy_user();
        $student = $this->dummy_user('student', 4);

        $video = UploadedFile::fake()->create('test.mp4', 5000);
        $course = Course::find(5);

        $this->actingAs($student)->get('api/courses/5/join');

        $uploadedVideo = $this->actingAs($admin)->post('api/videos', [
            'title' => 'test video',
            'course_id' => 5,
            'description' => 'test description',
            'video' => $video,
            'duration' => 120,
            'free' => true
        ]);

        $response = $this->actingAs($student)->get('api/videos/' . $uploadedVideo['id'] . '/stream');
        $response->assertStatus(200);
    }
}
