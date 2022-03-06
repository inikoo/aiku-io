<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 15 Jan 2022 04:11:47 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */


use App\Http\Controllers\Inventory\LocationController;
use App\Http\Controllers\Inventory\WarehouseAreaController;
use App\Http\Controllers\Inventory\WarehouseController;

Route::get('/', [WarehouseController::class, 'index'])->name('index');
Route::get('/{warehouse}', [WarehouseController::class, 'show'])->name('show');
Route::get('/{warehouse}/edit', [WarehouseController::class, 'edit'])->name('edit');
Route::post('/{warehouse}', [WarehouseController::class, 'update'])->name('update');

Route::get('/{warehouse}/locations', [LocationController::class, 'indexInWarehouse'])->name('show.locations.index');
Route::get('/{warehouse}/areas', [WarehouseAreaController::class, 'indexInWarehouse'])->name('show.areas.index');

Route::scopeBindings()->group(function () {

    Route::get('/{warehouse}/locations/{location}', [LocationController::class, 'showInWarehouse'])->name('show.locations.show')->scopeBindings();
    Route::get('/{warehouse}/locations/{location}/edit', [LocationController::class, 'editInWarehouse'])->name('show.locations.edit')->scopeBindings();


    Route::get('/{warehouse}/areas/{warehouseArea}', [WarehouseAreaController::class, 'showInWarehouse'])->name('show.areas.show')->scopeBindings();
    Route::get('/{warehouse}/areas/{warehouseArea}/edit', [WarehouseAreaController::class, 'editInWarehouse'])->name('show.areas.edit')->scopeBindings();
    Route::post('/{warehouse}/areas/{warehouseArea}', [WarehouseAreaController::class, 'updateInWarehouse'])->name('show.areas.update')->scopeBindings();


    Route::get('/{warehouse}/areas/{warehouseArea}/locations', [LocationController::class, 'indexInArea'])->name('show.areas.show.locations.index')->scopeBindings();
    Route::get('/{warehouse}/areas/{warehouseArea}/locations/{location}', [LocationController::class, 'showInArea'])->name('show.areas.show.locations.show');
    Route::get('/{warehouse}/areas/{warehouseArea}/locations/{location}/edit', [LocationController::class, 'editInArea'])->name('show.areas.show.locations.edit');
    Route::post('/{warehouse}/areas/{warehouseArea}/locations/{location}', [LocationController::class, 'update'])->name('show.areas.show.locations.update');

});


/*

Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
Route::get('/locations/{location}', [LocationController::class, 'show'])->name('locations.show');
Route::get('/locations/{location}/edit', [LocationController::class, 'edit'])->name('locations.edit');
Route::post('/locations/{location}', [LocationController::class, 'update'])->name('locations.update');

Route::get('/areas', [WarehouseAreaController::class, 'index'])->name('areas.index');
Route::get('/areas/{warehouseArea}', [WarehouseAreaController::class, 'show'])->name('areas.show');
Route::get('/areas/{warehouseArea}/edit', [WarehouseAreaController::class, 'edit'])->name('areas.edit');
Route::post('/areas/{warehouseArea}', [WarehouseAreaController::class, 'update'])->name('areas.update');









*/
