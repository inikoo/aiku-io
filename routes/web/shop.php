<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 12 Jan 2022 15:13:10 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

use App\Http\Controllers\Trade\ShopsController;


Route::get('/{shop}', [ShopsController::class, 'show'])->name('index');
Route::get('/customers', [ShopsController::class, 'index'])->name('customers.index');
Route::get('/orders', [ShopsController::class, 'index'])->name('orders.index');

