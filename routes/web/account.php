<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 21 Aug 2021 02:08:06 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use App\Http\Controllers\System\AccountController;
use App\Http\Controllers\System\UserController;

Route::get('/', [AccountController::class, 'show'])->name('show');
Route::get('/edit', [AccountController::class, 'edit'])->name('edit');
Route::post('/', [AccountController::class, 'update'])->name('update');


Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::post('/users/{user}', [UserController::class, 'update'])->name('users.update');


Route::get('/roles', [AccountController::class, 'roles'])->name('roles.index');
Route::get('/usage', [AccountController::class, 'index'])->name('usage');
Route::get('/logbook', [AccountController::class, 'logbook'])->name('logbook');
Route::get('/billing', [AccountController::class, 'billing'])->name('billing');


