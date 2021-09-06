<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 21 Aug 2021 02:08:06 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use App\Http\Controllers\System\SystemController;
use App\Http\Controllers\System\SystemSettingsController;

Route::get('/', [SystemController::class, 'index'])->name('index');
Route::get('/users', [SystemController::class, 'users'])->name('users');
Route::get('/roles', [SystemController::class, 'roles'])->name('roles');
Route::get('/usage', [SystemController::class, 'index'])->name('usage');
Route::get('/logbook', [SystemController::class, 'logbook'])->name('logbook');
Route::get('/billing', [SystemController::class, 'billing'])->name('billing');


Route::get('/settings', [SystemSettingsController::class, 'edit'])->name('settings');
Route::post('/settings', [SystemSettingsController::class, 'update'])->name('edit.settings');



