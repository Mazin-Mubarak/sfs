<?php

use App\Http\Controllers\EducationalInstitution\EducationalInstitutionsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('institutions', [EducationalInstitutionsController::class, 'store'])->name('institution.create');
    Route::post('institutions/{id}/phones', [EducationalInstitutionsController::class, 'addPhones'])->name('institution.add_phone_numbers');
});