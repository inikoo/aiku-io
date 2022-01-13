<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 12 Jan 2022 15:13:10 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

use App\Http\Controllers\B2BShops\B2BShopController;


Route::get('/{shop}', [B2BShopController::class, 'show'])->name('index');
Route::get('/customers', [B2BShopController::class, 'index'])->name('customers.index');
Route::get('/orders', [B2BShopController::class, 'index'])->name('orders.index');

