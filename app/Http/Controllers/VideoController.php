<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Payment;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('admin-only', except: ['show', 'stream'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videos = Video::all();

        $filteredVideos = [];

        foreach ($videos as $video) {
            $filteredVideos[] = [
                'id' => $video->id,
                'title' => $video->title,
                'course_id' => $video->course_id,
                'description' => $video->description,
                'video_url' => route('video.stream', ['video' => $video->id]),
                'duration' => $video->duration,
                'free' => $video->free
            ];
        }

        return response($filteredVideos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedFields = $request->validate([
            'title' => 'required',
            'course_id' => 'required',
            'description' => 'required',
            'video' => 'required|file|mimes:mp4',
            'duration' => 'required|numeric|min:0',
            'free' => 'required|boolean'
        ]);

        try {
            $validatedFields['video_url'] = $request->file('video')->store('courses-' . $validatedFields['course_id']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Video failed to upload', 'error' => $e->getMessage()], 500);
        }

        $video = Video::create($validatedFields);

        return response([
            'id' => $video->id,
            'title' => $video->title,
            'course_id' => $video->course_id,
            'description' => $video->description,
            'video_url' => route('video.stream', ['video' => $video->id]),
            'duration' => $video->duration,
            'free' => $video->free
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Video $video)
    {
        return response([
            'id' => $video->id,
            'title' => $video->title,
            'course_id' => $video->course_id,
            'description' => $video->description,
            'video_url' => route('video.stream', ['video' => $video->id]),
            'duration' => $video->duration,
            'free' => $video->free
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Video $video)
    {
        $validatedFields = $request->validate([
            'title' => 'required',
            'course_id' => 'required',
            'description' => 'required',
            'video' => 'required|file|mimes:mp4',
            'duration' => 'required|numeric|min:0',
            'free' => 'required|boolean'
        ]);

        Storage::delete($video->video_url);
        $validatedFields['video_url'] = $request->video->store('courses-' . $validatedFields['course_id']);

        $video->update($validatedFields);

        return response([
            'id' => $video->id,
            'title' => $video->title,
            'course_id' => $video->course_id,
            'description' => $video->description,
            'video_url' => route('video.stream', ['video' => $video->id]),
            'duration' => $video->duration,
            'free' => $video->free
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video)
    {
        Storage::delete($video->video_url);
        $video->delete();

        return response(null, 204);
    }

    public function stream(Video $video) {
        Gate::authorize('stream', $video);

        $path = Storage::path($video->video_url);

        // dd($path);
        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Content-Type' => 'video/mp4',
        ]);
    }
}
