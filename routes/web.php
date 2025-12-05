<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SendEmailController; // 👈 important

Route::get('/', function () {
    return view('landing'); // or whatever your blade is
});

Route::view('/', 'index');

// POST route used by your JS
Route::post('/send-email', [SendEmailController::class, 'handle'])
    ->name('send-email');
