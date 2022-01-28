<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 20 Jan 2022 14:41:05 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\Inventory\StockController;
use App\Http\Controllers\Inventory\WarehouseController;

Route::get('/', [InventoryController::class, 'dashboard'])->name('dashboard');

Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
Route::get('/stocks/{stock}', [StockController::class, 'show'])->name('stocks.show');
Route::get('/stocks/{stock}/edit', [StockController::class, 'edit'])->name('stocks.edit');
Route::post('/stocks/{stock}', [StockController::class, 'update'])->name('stocks.update');

Route::get('/warehouses/{warehouse}', [WarehouseController::class, 'show'])->name('warehouses.show');
Route::get('/warehouses/{warehouse}/locations', [LocationController::class, 'index'])->name('warehouses.show.locations.index');
Route::get('/warehouses/{warehouse}/locations/{location}', [LocationController::class, 'show'])->name('warehouses.show.locations.show');


Route::get('/warehouses/{warehouse}/areas', [WarehouseAreaController::class, 'index'])->name('warehouses.show.areas.index');
Route::get('/warehouses/{warehouse}/areas/{area}', [WarehouseAreaController::class, 'index'])->name('warehouses.show.areas.show');
