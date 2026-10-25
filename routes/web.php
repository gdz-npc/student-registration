<?php

use Illuminate\Support\Facades\Route;

// Redirect the homepage to the student interface.
Route::get('/', function () {
    return redirect()->route('students.index');
});

// Student listing page.
Route::view('/students', 'students.index')
    ->name('students.index');

// Student registration page.
Route::view('/students/create', 'students.create')
    ->name('students.create');

// Student editing page.
Route::view('/students/{id}/edit', 'students.edit')
    ->whereNumber('id')
    ->name('students.edit');

// Student details page.
Route::view('/students/{id}', 'students.show')
    ->whereNumber('id')
    ->name('students.show');

// Email OTP page.
Route::view('/email-otp', 'otp.index')
    ->name('otp.index');