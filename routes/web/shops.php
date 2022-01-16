<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 11 Jan 2022 03:46:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */


use App\Http\Controllers\Shop\ShopController;


Route::get('/', [ShopController::class, 'index'])->name('index');
Route::get('/customers', [ShopController::class, 'index'])->name('customers.index');
Route::get('/orders', [ShopController::class, 'index'])->name('orders.index');


Route::get('/{shop}', [ShopController::class, 'show'])->name('show');
Route::get('/{shop}/customers', [ShopController::class, 'index'])->name('store.customers.index');
Route::get('/{shop}/orders', [ShopController::class, 'index'])->name('store.orders.index');


