<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 11 Jan 2022 03:46:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */


use App\Http\Controllers\Marketing\MarketingController;
use App\Http\Controllers\Marketing\CustomerController;
use App\Http\Controllers\Marketing\ShopController;

Route::get('/', [MarketingController::class, 'dashboard'])->name('dashboard');

Route::get('/shops', [ShopController::class, 'index'])->name('shops.index');
Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
Route::get('/orders', [ShopController::class, 'index'])->name('orders.index');


Route::get('/shops/{shop}', [ShopController::class, 'show'])->name('shops.show');

Route::get('/shops/{shop}/customers', [CustomerController::class, 'indexInShop'])->name('shops.show.customers.index');
Route::get('/shops/{shop}/customers/{customer}', [CustomerController::class, 'showInShop'])->name('shops.show.customers.show');
Route::get('/shops/{shop}/orders', [ShopController::class, 'index'])->name('shops.show.orders.index');


