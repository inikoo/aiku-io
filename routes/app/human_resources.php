<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 21 Aug 2021 04:40:33 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use App\Http\Controllers\HumanResources\EmployeeController;
use App\Http\Controllers\HumanResources\HumanResourcesController;
use App\Http\Controllers\HumanResources\WorkTargetsController;


Route::get('/', [HumanResourcesController::class, 'dashboard'])->name('dashboard');
Route::get('/logbook', [HumanResourcesController::class, 'logbook'])->name('logbook');

Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
Route::post('/employees/create', [EmployeeController::class, 'store'])->name('employees.store');
Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
Route::post('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');

Route::scopeBindings()->group(function () {
    Route::get('/employees/{employee}/timesheets', [WorkTargetsController::class, 'indexInEmployee'])->name('employees.show.timesheets.index');
    Route::get('/employees/{employee}/timesheets/{work_target}', [WorkTargetsController::class, 'showInEmployee'])->name('employees.show.timesheets.show');
    Route::get('/employees/{employee}/timesheets/{work_target}/edit', [WorkTargetsController::class, 'editInEmployee'])->name('employees.show.timesheets.edit');
});


Route::get('/employees/logbook', [EmployeeController::class, 'logbook'])->name('employees.logbook');




Route::get('/timesheets', [WorkTargetsController::class, 'indexInTenant'])->name('timesheets.index');
Route::get('/timesheets/{interval}', [WorkTargetsController::class, 'indexInTenantWithInterval'])->name('timesheets.interval');

Route::get('/timesheets/{work_target}', [WorkTargetsController::class, 'showInTenant'])->name('timesheets.show');

Route::get('/timesheets/{work_target}/edit', [WorkTargetsController::class, 'editInTenant'])->name('timesheets.edit');
Route::post('/timesheets/{work_target}', [WorkTargetsController::class, 'update'])->name('timesheets.update');

Route::get('/working_hours/{interval}', [WorkTargetsController::class, 'indexInTenantWithInterval'])->name('working_hours.interval');

