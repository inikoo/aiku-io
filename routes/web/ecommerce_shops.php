<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 11 Jan 2022 03:46:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */


use App\Http\Controllers\Shops\CustomerController;
use App\Http\Controllers\Trade\ShopController;


Route::get('/', [ShopController::class, 'index'])->name('index');
Route::get('/customers', [CustomerController::class, 'indexInEcommerceShops'])->name('customers.index');
Route::get('/orders', [ShopController::class, 'index'])->name('orders.index');


Route::get('/{shop}', [ShopController::class, 'show'])->name('show');

Route::get('/{shop}/customers', [CustomerController::class, 'indexInShop'])->name('show.customers.index');
Route::get('/{shop}/customers/{customer}', [CustomerController::class, 'showInShop'])->name('show.customers.show');

Route::get('/{shop}/orders', [ShopController::class, 'index'])->name('show.orders.index');


