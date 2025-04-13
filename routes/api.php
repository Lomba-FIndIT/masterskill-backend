<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function() {
    Route::get('logout', [AuthController::class, 'logout']);

    Route::apiResource('courses', CourseController::class);
    Route::get('courses/{course}/join', [CourseController::class, 'attach'])->middleware('student-only');
    Route::delete('courses/{course}/join', [CourseController::class, 'detach'])->middleware('student-only');
    Route::get('courses/{course}/students', [CourseController::class, 'students']);

    Route::apiResource('users', UserController::class);
});