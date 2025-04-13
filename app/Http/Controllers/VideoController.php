<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

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
            'video' => 'required|file|mimes:mp4'
        ]);

        $validatedFields['video_url'] = $request->video->store('courses-' . $validatedFields['course_id']);

        $video = Video::create($validatedFields);

        return response([
            'id' => $video->id,
            'title' => $video->title,
            'course_id' => $video->course_id,
            'description' => $video->description,
            'video_url' => asset('storage/' . $video->video_url)
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video)
    {
        //
    }
}
