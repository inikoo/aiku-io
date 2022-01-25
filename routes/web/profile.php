<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 22 Jan 2022 15:19:20 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

use App\Http\Controllers\System\ProfileController;

Route::get('/', [ProfileController::class, 'show'])->name('show');
Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
Route::post('/', [ProfileController::class, 'update'])->name('update');

Route::get('/roles', [ProfileController::class, 'roles'])->name('roles.index');

