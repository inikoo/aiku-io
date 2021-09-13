<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 21 Aug 2021 04:40:33 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use App\Http\Controllers\HumanResources\EmployeesController;
use App\Http\Controllers\HumanResources\HumanResourcesController;
use App\Http\Controllers\HumanResources\TimesheetsController;


Route::get('/', [HumanResourcesController::class, 'index'])->name('index');
Route::get('/logbook', [HumanResourcesController::class, 'logbook'])->name('logbook');

Route::get('/employees', [EmployeesController::class, 'index'])->name('employees.index');
Route::get('/employees/logbook', [EmployeesController::class, 'logbook'])->name('employees.logbook');

Route::get('/employees/create', [EmployeesController::class, 'create'])->name('employees.create');
Route::post('/employees/create', [EmployeesController::class, 'store'])->name('employees.store');


Route::get('/timesheets', [TimesheetsController::class, 'index'])->name('timesheets.index');
