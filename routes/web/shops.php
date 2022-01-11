<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 11 Jan 2022 03:46:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */


use App\Http\Controllers\Trade\ShopsController;


Route::get('/', [ShopsController::class, 'index'])->name('index');
Route::get('/customers', [ShopsController::class, 'index'])->name('customers.index');
Route::get('/orders', [ShopsController::class, 'index'])->name('orders.index');



Route::get('/{id}', [ShopsController::class, 'show'])->name('show');

//Route::get('/logbook', [HumanResourcesController::class, 'logbook'])->name('logbook');

//Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
//Route::get('/employees/logbook', [EmployeeController::class, 'logbook'])->name('employees.logbook');
//Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
//Route::post('/employees/create', [EmployeeController::class, 'store'])->name('employees.store');


//Route::get('/timesheets', [TimesheetsController::class, 'index'])->name('timesheets.index');
