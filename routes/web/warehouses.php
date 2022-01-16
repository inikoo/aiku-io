<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 15 Jan 2022 04:11:47 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */


use App\Http\Controllers\Inventory\WarehouseController;

Route::get('/', [WarehouseController::class, 'index'])->name('index');
Route::get('/{warehouse}', [WarehouseController::class, 'show'])->name('show');
