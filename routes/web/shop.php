<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 12 Jan 2022 15:13:10 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

use App\Http\Controllers\Shop\ShopController;


Route::get('/{shop}', [ShopController::class, 'show'])->name('index');
Route::get('/customers', [ShopController::class, 'index'])->name('customers.index');
Route::get('/orders', [ShopController::class, 'index'])->name('orders.index');

