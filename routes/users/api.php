<?php

/**
 * This file contains all endpoints for users API requests
 */

use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::post('users/register', [UserController::class, 'store']);
Route::post('users/login', [UserController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('users/logout', [UserController::class, 'logout']);

    Route::post('users/current/phones', [UserController::class,  'addPhone'])->name('users.add_phone');
    Route::post('users/current/emails', [UserController::class,  'addEmail'])->name('users.add_email');
});