<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 11 Jan 2022 03:46:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */


use App\Http\Controllers\Inventory\UniqueStockController;
use App\Http\Controllers\Marketing\CustomerController;
use App\Http\Controllers\Marketing\DepartmentController;
use App\Http\Controllers\Marketing\FamilyController;
use App\Http\Controllers\Marketing\MarketingController;
use App\Http\Controllers\Marketing\ProductController;
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

Route::get('/shops/{shop}/catalogue', [ShopController::class, 'catalogue'])->name('shops.show.catalogue');

Route::patch('/shops/{shop}/catalogue', [ShopController::class, 'setCatalogue'])->name('shops.show.catalogue.set');

Route::get('/shops/{shop}/departments/', [DepartmentController::class, 'index'])->name('shops.show.departments.index');
Route::get('/shops/{shop}/families/', [FamilyController::class, 'indexInShop'])->name('shops.show.families.index');
Route::get('/shops/{shop}/products/', [ProductController::class, 'indexInShop'])->name('shops.show.products.index');


Route::scopeBindings()->group(function () {

    Route::get('/shops/{shop}/departments/{family}', [DepartmentController::class, 'showInShop'])->name('shops.show.departments.show');


    Route::get('/shops/{shop}/families/{family}', [FamilyController::class, 'showInShop'])->name('shops.show.families.show');


    Route::get('/shops/{shop}/products/{product}', [ProductController::class, 'showInShop'])->name('shops.show.products.show');

});
