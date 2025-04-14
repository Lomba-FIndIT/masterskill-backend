<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

        $validatedFields['video_url'] = $request->video->store('courses-' . $validatedFields['course_id']);

        $video = Video::create($validatedFields);

        return response([
            'id' => $video->id,
            'title' => $video->title,
            'course_id' => $video->course_id,
            'description' => $video->description,
            'video_url' => asset('storage/' . $video->video_url),
            'duration' => $video->duration,
            'free' => $video->free
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Video $video)
    {
        //
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
            'video_url' => asset('storage/' . $video->video_url),
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
}
