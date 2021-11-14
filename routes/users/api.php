<?php

/**
 * This file contains all endpoints for users API requests
 */

use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::post('users/register', [UserController::class, 'store']);