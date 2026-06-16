<?php

declare(strict_types=1);

use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\SessionsController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

Route::get('/register', [RegisterUserController::class, 'create']);
Route::get('/login', [SessionsController::class, 'create']);
