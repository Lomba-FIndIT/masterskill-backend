<?php

namespace Tests\Feature;

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
        - admin can update video details
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

        Storage::fake('courses-5');

        $video = UploadedFile::fake()->create('test.mp4', 5000);

        $response = $this->actingAs($admin)->post('api/videos', [
            'title' => 'test video',
            'course_id' => 5,
            'description' => 'test description',
            'video' => $video
        ]);
        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'title',
                'course_id',
                'description',
                'video_url'
            ]);
        $this->assertDatabaseHas('videos', [
            'id' => $response['id'],
        ]);
    }
}
