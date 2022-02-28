<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 11 Jan 2022 04:56:47 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

use App\Http\Controllers\FulfilmentHouse\FulfilmentHouseController;


Route::get('/', [FulfilmentHouseController::class, 'index'])->name('index');
Route::get('/{shop}', [FulfilmentHouseController::class, 'show'])->name('show');
