<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

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
        $users = User::all();

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

        return response($user, 201);
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
            'img_url' => asset('storage/' . $user['img_url'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validatedFields = $request->validate([
            'name' => 'required',
            'email' =>'required|email',
            'role_id' => 'required',
            'phone_number' => 'required',
            'address' => 'required',
            'img' => 'required|file|mimes:png,jpg,pdf'
        ]);

        // if ($user->img_url !== null) {
        //     Storage
        // }

        if ($request->hasFile('img')) {
            $validatedFields['img_url'] = $request->img->store('profiles');
        }

        $user->update($validatedFields);

        return response($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
