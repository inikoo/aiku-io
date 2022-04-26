<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 06 Apr 2022 17:58:14 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */


use App\Http\Controllers\Staffing\ApplicantController;
use App\Http\Controllers\Staffing\RecruiterController;
use App\Http\Controllers\Staffing\StaffingController;

Route::get('/', [StaffingController::class, 'dashboard'])->name('dashboard');


Route::get('/recruiters', [RecruiterController::class, 'index'])->name('recruiters.index');
Route::get('/recruiters/{{recruiter}}', [RecruiterController::class, 'show'])->name('recruiters.show');
Route::get('/recruiters/{{recruiter}}/edit', [RecruiterController::class, 'edit'])->name('recruiters.edit');
Route::post('/recruiters/{recruiter}', [RecruiterController::class, 'update'])->name('recruiters.update');


Route::get('/applicants', [ApplicantController::class, 'index'])->name('applicants.index');
Route::get('/applicants/{{applicant}}', [ApplicantController::class, 'show'])->name('applicants.show');
Route::get('/applicants/{{applicant}}/edit', [ApplicantController::class, 'edit'])->name('applicants.edit');
Route::post('/applicants/{applicant}', [ApplicantController::class, 'update'])->name('applicants.update');
