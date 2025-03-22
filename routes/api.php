<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Course\CourseController;
use App\Http\Controllers\Api\CourseCategory\CourseCategoryController;
use App\Http\Controllers\Api\CourseType\CourseTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])->prefix('auth-client')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('verify-reset-code', [AuthController::class, 'verifyResetCode']);
    Route::post('password/reset', [AuthController::class, 'resetPassword'])->name('password.reset');
    Route::get('{provider}/redirect', [AuthController::class, 'redirectToProvider']);
    Route::get('{provider}/callback', [AuthController::class, 'handleProviderCallback']);
    Route::post('social-login/{provider}', [AuthController::class, 'handleSocialAuth']);
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);


});

Route::middleware(['api'])->prefix('admin')->group(function () {
   Route::resource('course-types', CourseTypeController::class);
   Route::resource('course-categories', CourseCategoryController::class);
   Route::resource('courses', CourseController::class);
});


