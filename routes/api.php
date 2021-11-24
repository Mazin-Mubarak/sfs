<?php

use App\Http\Controllers\EmailController;
use App\Http\Controllers\PhoneNumberController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// get all (require) endpoint for user API from ./user/api.php file
require __DIR__.DIRECTORY_SEPARATOR."users".DIRECTORY_SEPARATOR."api.php";
require __DIR__.DIRECTORY_SEPARATOR."educationalInstitutions".DIRECTORY_SEPARATOR."api.php";

Route::post('phones/{id}/verify', [PhoneNumberController::class, 'verify'])->name('phone.verify.otp');
Route::get('phones/{id}/verify/{token}', [PhoneNumberController::class, 'verifyByToken'])->name('phone.verify.token');


Route::post('emails/{id}/verify', [EmailController::class, 'verify'])->name('email.verify.otp');
Route::get('emails/{id}/verify/{token}', [EmailController::class, 'verifyByToken'])->name('email.verify.token');
