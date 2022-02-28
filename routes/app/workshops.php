<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 15 Jan 2022 22:59:51 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

use App\Http\Controllers\Production\WorkshopController;

Route::get('/', [WorkshopController::class, 'index'])->name('index');

Route::get('/{workshop}', [WorkshopController::class, 'show'])->name('show');
