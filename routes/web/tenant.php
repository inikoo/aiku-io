<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 21 Aug 2021 02:08:06 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use App\Http\Controllers\System\TenantController;
use App\Http\Controllers\System\TenantSettingsController;
use App\Http\Controllers\System\UserController;

Route::get('/', [TenantController::class, 'show'])->name('show');
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/{user}', [TenantController::class, 'user'])->name('users.show');
Route::get('/roles', [TenantController::class, 'roles'])->name('roles.index');
Route::get('/usage', [TenantController::class, 'index'])->name('usage');
Route::get('/logbook', [TenantController::class, 'logbook'])->name('logbook');
Route::get('/billing', [TenantController::class, 'billing'])->name('billing');


Route::get('/settings', [TenantSettingsController::class, 'edit'])->name('settings');
Route::post('/settings', [TenantSettingsController::class, 'update'])->name('edit.settings');



