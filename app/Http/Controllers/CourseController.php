<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('admin-only', only: ['store', 'update', 'destroy']),
            new Middleware('auth:sanctum', except: ['index'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::all();

        $filteredCourses = [];

        foreach ($courses as $course) {
            $category = Category::find($course->category_id)->category_name;
            $instructor = User::find($course->instructor_id)->name;

            $filteredCourses[] = [
                'id' => $course->id,
                'course_name' => $course->course_name,
                'category' => $category,
                'instructor' => $instructor,
                'price' => $course->price,
                'img_url' => asset('storage/' . $course->img_url),
                'ratings' => $course->ratings,
                'total_duration' => $course->total_duration
            ];
        }

        return response($filteredCourses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedFields = $request->validate([
            'course_name' => 'required',
            'category_id' => 'required',
            'instructor_id' => 'required',
            'price' => 'required',
            'img' => 'required|file|mimes:png,jpg,pdf'
        ]);

        $course = Course::create($validatedFields);
        $course->refresh();

        $validatedFields['img_url'] = $request->img->store('courses-' . $course->id, 'public');

        $course->update($validatedFields);

        $category = Category::find($course->category_id)->category_name;
        $instructor = User::find($course->instructor_id)->name;

        return response([
            'id' => $course->id,
            'course_name' => $course->course_name,
            'category' => $category,
            'instructor' => $instructor,
            'price' => $course->price,
            'img_url' => asset('storage/' . $course->img_url),
            'ratings' => $course->ratings,
            'total_duration' => $course->total_duration
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        $category = Category::find($course->category_id)->category_name;
        $instructor = User::find($course->instructor_id)->name;

        $filteredCourse = [
            'id' => $course->id,
                'course_name' => $course->course_name,
                'category' => $category,
                'instructor' => $instructor,
                'price' => $course->price,
                'img_url' => asset('storage/' . $course->img_url),
                'ratings' => $course->ratings,
                'total_duration' => $course->total_duration
        ];
        
        return response($filteredCourse);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validatedFields = $request->validate([
            'course_name' => 'required',
            'category_id' => 'required',
            'instructor_id' => 'required',
            'price' => 'required',
            'img' => 'required|file|mimes:png,jpg,pdf'
        ]);

        if ($request->hasFile('img')) {
            Storage::delete($course->img_url);
            
            $validatedFields['img_url'] = $request->img->store('courses-' . $course->id, 'public');
        }
        
        $course->update($validatedFields);

        $category = Category::find($course->category_id)->category_name;
        $instructor = User::find($course->instructor_id)->name;
        
        return response([
            'id' => $course->id,
            'course_name' => $course->course_name,
            'category' => $category,
            'instructor' => $instructor,
            'price' => $course->price,
            'img_url' => asset('storage/' . $course->img_url),
            'ratings' => $course->ratings,
            'total_duration' => $course->total_duration
        ]);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        Storage::delete($course->img_url);
        $course->delete();

        return response(null, 204);
    }

    public function attach(Request $request, Course $course)
    {
        $user = $request->user();

        $payment = Payment::create([
            'student_id' => $user->id
        ]);

        $course->students()->attach($user->id, ['payment_id' => $payment->id, 'rating' => 0]);

        $ratings = $course->students()->wherePivot('rating', '>', 0)->avg('course_user.rating');

        $course->update([
            'ratings' => $ratings !== null ? $ratings : 0
        ]);

        return response(['message' => 'successfully joined course ' . $course->name], 201);
    }

    public function detach(Request $request, Course $course)
    {
        $user = $request->user();

        $student = $course->students()->wherePivot('user_id', $user->id)->first();

        $payment = Payment::find($student->pivot->payment_id);
        $payment->delete();

        $course->students()->detach($user->id);

        $ratings = $course->students()->wherePivot('rating', '>', 0)->avg('course_user.rating');

        $course->update([
            'ratings' => $ratings !== null ? $ratings : 0
        ]);

        return response(null, 204);
    }

    public function students(Course $course)
    {
        $students = $course->students;

        return response($students);
    }

    public function rate(Request $request, Course $course)
    {
        Gate::authorize('rate', $course);

        $validatedFields = $request->validate([
            'rate' => 'required|numeric|max:5|min:0'
        ]);

        $user = $request->user();

        $student = $course->students()->wherePivot('user_id', $user->id)->first();

        $student->pivot->update([
            'rating' => $validatedFields['rate']
        ]);

        $ratings = $course->students()->wherePivot('rating', '>', 0)->avg('course_user.rating');

        $course->update([
            'ratings' => $ratings !== null ? $ratings : 0
        ]);

        return response(['message' => 'rating given ' . $validatedFields['rate']], 200);
    }

    public function byCategory (Category $category) {
        $courses = Course::where('category_id', '=', $category->id)->get();

        $filteredCourses = [];

        foreach ($courses as $course) {
            $instructor = User::find($course->instructor_id)->name;

            $filteredCourses[] = [
                'id' => $course->id,
                'course_name' => $course->course_name,
                'category' => $category->category_name,
                'instructor' => $instructor,
                'price' => $course->price,
                'img_url' => asset('storage/' . $course->img_url),
                'ratings' => $course->ratings,
                'total_duration' => $course->total_duration
            ];
        }

        return response($filteredCourses);
    }
}
