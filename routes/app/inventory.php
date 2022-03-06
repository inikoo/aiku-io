<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 20 Jan 2022 14:41:05 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\Inventory\LocationController;
use App\Http\Controllers\Inventory\StockController;
use App\Http\Controllers\Inventory\UniqueStockController;
use App\Http\Controllers\Inventory\WarehouseAreaController;

Route::get('/', [InventoryController::class, 'dashboard'])->name('dashboard');

Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
Route::get('/stocks/{stock}', [StockController::class, 'show'])->name('stocks.show');
Route::get('/stocks/{stock}/edit', [StockController::class, 'edit'])->name('stocks.edit');
Route::post('/stocks/{stock}', [StockController::class, 'update'])->name('stocks.update');

Route::get('/stock_locations/', [StockController::class, 'show'])->name('stock_locations.index');


Route::get('/stored_goods', [UniqueStockController::class, 'IndexUniqueStockInTenant'])->name('unique_stocks.index');
Route::get('/stored_goods/{unique_stock}', [UniqueStockController::class, 'ShowUniqueStockInTenant'])->name('unique_stocks.show');


Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
Route::get('/locations/{location}', [LocationController::class, 'show'])->name('locations.show');
Route::get('/locations/{location}/edit', [LocationController::class, 'edit'])->name('locations.edit');
Route::post('/locations/{location}', [LocationController::class, 'update'])->name('locations.update');

Route::get('/areas', [WarehouseAreaController::class, 'indexInTenant'])->name('areas.index');
Route::get('/areas/{warehouseArea}', [WarehouseAreaController::class, 'show'])->name('areas.show');
Route::get('/areas/{warehouseArea}/edit', [WarehouseAreaController::class, 'edit'])->name('areas.edit');
Route::post('/areas/{warehouseArea}', [WarehouseAreaController::class, 'update'])->name('areas.update');

Route::get('/areas/{warehouseArea}/locations', [LocationController::class, 'indexInArea'])->name('areas.show.locations.index')->scopeBindings();
Route::scopeBindings()->group(function () {
    Route::get('/areas/{warehouseArea}/locations/{location}', [LocationController::class, 'showInArea'])->name('areas.show.locations.show');
    Route::get('/areas/{warehouseArea}/locations/{location}/edit', [LocationController::class, 'editInArea'])->name('areas.show.locations.edit');
    Route::post('/areas/{warehouseArea}/locations/{location}', [LocationController::class, 'update'])->name('areas.show.locations.update');
});

Route::get('/fulfilment_stocks', [StockController::class, 'index'])->name('fulfilment_stocks.index');

Route::prefix('warehouses')
    ->name('warehouses.')
    ->group(__DIR__.'/warehouses.php');

