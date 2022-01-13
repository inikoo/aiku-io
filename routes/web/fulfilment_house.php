<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 12 Jan 2022 22:25:03 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

use App\Http\Controllers\FulfilmentHouse\FulfilmentHouseController;


Route::get('/{shop}', [FulfilmentHouseController::class, 'show'])->name('index');


