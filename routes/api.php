<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\StudentController;
use App\Http\Controllers\Api\V2\StudentController as StudentV2Controller;
use App\Http\Controllers\Api\V1\EmailOtpController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::apiResource('students', StudentController::class);
    Route::post('/email-otp/send', [EmailOtpController::class, 'send']);
    Route::post('/email-otp/verify', [EmailOtpController::class, 'verify']);
});

Route::prefix('v2')->group(function () {
    Route::apiResource('students', StudentV2Controller::class);
});