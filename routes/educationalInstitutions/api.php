<?php

use App\Http\Controllers\EducationalInstitution\EducationalInstitutionsController;

use App\Http\Controllers\EducationalInstitution\EmployeesController;
use App\Http\Controllers\EducationalInstitution\PhonesController;
use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('institutions', [EducationalInstitutionsController::class, 'store'])->name('institution.create');
    
    Route::post('institutions/{id}/phones', [PhonesController::class, 'store'])->name('institution.phones.add');
    Route::post('institutions/{id}/emails', [EmailController::class, 'store'])->name('institution.emails.add');

    Route::post('institutions/{id}/employees', [EmployeesController::class, 'store'])->name('institution.employees.add');

    Route::post('institutions/employees/requests/{id}/accept', [EmployeesController::class, 'accept'])->name('institution.employees.requests.accept');
    Route::post('institutions/employees/requests/{id}/decline', [EmployeesController::class, 'decline'])->name('institution.employees.requests.decline');

});