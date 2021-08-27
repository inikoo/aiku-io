<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 21 Aug 2021 04:40:33 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use App\Http\Controllers\Health\PatientController;




Route::get('/', [PatientController::class, 'index'])->name('index');

Route::get('/create', [PatientController::class, 'create'])->name('create');
Route::post('/create', [PatientController::class, 'store'])->name('store');

Route::get('/{id}', [PatientController::class, 'show'])->name('show');
Route::get('/{id}/edit', [PatientController::class, 'edit'])->name('edit');
Route::post('/{id}/edit', [PatientController::class, 'update'])->name('update');


Route::get('/logbook', [PatientController::class, 'logbook'])->name('logbook');
Route::get('/{id}/logbook', [PatientController::class, 'patientLogbook'])->name('show.logbook');

