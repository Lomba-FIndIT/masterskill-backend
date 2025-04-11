<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CourseController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('admin-only', only: ['store', 'update', 'destro'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::all();

        return response($courses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedFields = $request->validate([
            'course_name' => 'required',
            'category_id' => 'required',
            'instructor_id' => 'required'
        ]);

        $course = Course::create($validatedFields);

        return response($course, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        return response($course);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validatedFields = $request->validate([
            'course_name' => 'required',
            'category_id' => 'required',
            'instructor_id' => 'required'
        ]);

        $course->update($validatedFields);

        return response($course);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        //
    }
}
