<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 20 Mar 2022 00:57:56 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

use App\Http\Controllers\Inventory\UniqueStockController;
use App\Http\Controllers\Marketing\CustomerController;
use App\Http\Controllers\Marketing\MarketingController;
use App\Http\Controllers\Marketing\ShopController;

Route::get('/', [MarketingController::class, 'dashboard'])->name('dashboard');

Route::get('/shops', [ShopController::class, 'index'])->name('shops.index');
Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
Route::get('/orders', [ShopController::class, 'index'])->name('orders.index');


Route::get('/shops/{shop}', [ShopController::class, 'show'])->name('shops.show');

Route::get('/shops/{shop}/customers', [CustomerController::class, 'indexInShop'])->name('shops.show.customers.index');
Route::get('/shops/{shop}/unique_stocks', [UniqueStockController::class, 'indexUniqueStocksInShop'])->name('shops.show.unique_stocks.index');
Route::scopeBindings()->group(function () {
    Route::get('/shops/{shop}/unique_stocks/{unique_stock}', [UniqueStockController::class, 'showUniqueStocksInShop'])->name('shops.show.unique_stocks.show');
});

Route::scopeBindings()->group(function () {
    Route::get('/shops/{shop}/customers/{customer}', [CustomerController::class, 'showInShop'])->name('shops.show.customers.show');
    Route::get('/shops/{shop}/customers/{customer}/unique_stocks', [UniqueStockController::class, 'indexUniqueStockInCustomer'])->name('shops.show.customers.show.unique_stocks.index');
    Route::get('/shops/{shop}/customers/{customer}/unique_stocks/{unique_stock}', [UniqueStockController::class, 'showUniqueStockInCustomer'])->name('shops.show.customers.show.unique_stocks.show');

});

Route::get('/shops/{shop}/orders', [ShopController::class, 'index'])->name('shops.show.orders.index');


