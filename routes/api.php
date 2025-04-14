<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login'])->name('login');

Route::apiResource('courses', CourseController::class);
Route::middleware('auth:sanctum')->group(function() {
    Route::get('logout', [AuthController::class, 'logout']);

    Route::get('courses/{course}/join', [CourseController::class, 'attach'])->middleware('student-only');
    Route::delete('courses/{course}/join', [CourseController::class, 'detach'])->middleware('student-only');
    Route::get('courses/{course}/students', [CourseController::class, 'students']);
    Route::post('courses/{course}/rate', [CourseController::class, 'rate']);

    Route::apiResource('users', UserController::class);
    Route::get('user', [UserController::class, 'loginUser']);
    Route::get('users/{user}/courses', [UserController::class, 'courses']);

    Route::apiResource('videos', VideoController::class);
    Route::get('videos/{video}/stream', [VideoController::class, 'stream'])->name('video.stream');
});