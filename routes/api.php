<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/register/check', [AuthController::class, 'checkRegistration']);
Route::post('/register', [AuthController::class, 'register']);