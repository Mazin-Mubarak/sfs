<?php

use App\Http\Controllers\EducationalInstitution\EducationalInstitutionsController;

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('institutions', [EducationalInstitutionsController::class, 'store'])->name('institution.create');
});