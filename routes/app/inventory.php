<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 20 Jan 2022 14:41:05 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\Inventory\StockController;
use App\Http\Controllers\Inventory\UniqueStockController;
use App\Http\Controllers\Inventory\WarehouseController;

Route::get('/', [InventoryController::class, 'dashboard'])->name('dashboard');

Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
Route::get('/stocks/{stock}', [StockController::class, 'show'])->name('stocks.show');
Route::get('/stocks/{stock}/edit', [StockController::class, 'edit'])->name('stocks.edit');
Route::post('/stocks/{stock}', [StockController::class, 'update'])->name('stocks.update');

Route::get('/stock_locations/', [StockController::class, 'show'])->name('stock_locations.index');

Route::get('/stored_goods', [UniqueStockController::class, 'IndexUniqueStockInTenant'])->name('stored_goods.index');
Route::get('/stored_goods/{unique_stock}', [UniqueStockController::class, 'ShowUniqueStockInTenant'])->name('stored_goods.show');


Route::get('/fulfilment_stocks', [StockController::class, 'index'])->name('fulfilment_stocks.index');
