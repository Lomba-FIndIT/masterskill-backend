<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('admin-only', only: ['store', 'index'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all()->makeHidden(['password', 'remember_token', 'img_url']);

        return response($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedFields = $request->validate([
            'name' => 'required',
            'email' =>'required|email',
            'password' => 'required|confirmed|min:6',
            'role_id' => 'required',
            'phone_number' => 'required'
        ]);

        if ($validatedFields['role_id'] !== 2 && $validatedFields['role_id'] !== 3) {
            return response(['message' => 'invalid role'], 400);
        }

        if ($request['address'] !== null) {
            $validatedFields['address'] = $request['address'];
        }

        $user = User::create($validatedFields);

        return response([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'role_id' => $user->role_id,
            'address' => $user->address,
            'img_url' => $user->img_url === null ? null : asset('storage/' . $user->img_url)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        Gate::authorize('view', $user);

        return response([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'role_id' => $user->role_id,
            'address' => $user->address,
            'img_url' => $user->img_url === null ? null : asset('storage/' . $user->img_url)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        Gate::authorize('modify', $user);

        $validatedFields = $request->validate([
            'name' => 'required',
            'email' =>'required|email',
            'role_id' => 'required',
            'phone_number' => 'required',
            'address' => 'required',
            'img' => 'required|file|mimes:png,jpg,pdf'
        ]);

        if ($request->hasFile('img')) {

            if ($user->img_url !== null) {
                Storage::delete($user->img_url);
            }
            
            $validatedFields['img_url'] = $request->img->store('profiles', 'public');
        }

        $user->update($validatedFields);

        return response([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'role_id' => $user->role_id,
            'address' => $user->address,
            'img_url' => asset('storage/' . $user['img_url'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);

        if ($user->img_url !== null) {
            Storage::delete($user->img_url);
        }

        $user->delete();

        return response(['message' => 'success deleted'], 204);
    }
    
    public function loginUser(Request $request)
    {
        $user = $request->user();

        return response([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'role_id' => $user->role_id,
            'address' => $user->address,
            'img_url' => asset('storage/' . $user['img_url'])
        ]);
    }

    public function courses(Request $request)
    {
        // policy

        $user = $request->user();

        $applied_courses = $user->applied_courses;

        $filteredCourses = [];

        foreach ($applied_courses as $course) {
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
}
