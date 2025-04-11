<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// main api

Route::post('api/register', [AuthController::class, 'register']);
Route::post('api/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function() {
    Route::get('api/logout', [AuthController::class, 'logout']);

    Route::apiResource('api/courses', CourseController::class);
});