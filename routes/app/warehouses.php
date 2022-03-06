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

    Route::get('/{warehouse}/locations/{location}', [LocationController::class, 'showInWarehouse'])->name('show.locations.show');
    Route::get('/{warehouse}/locations/{location}/edit', [LocationController::class, 'editInWarehouse'])->name('show.locations.edit');


    Route::get('/{warehouse}/areas/{warehouseArea}', [WarehouseAreaController::class, 'showInWarehouse'])->name('show.areas.show');
    Route::get('/{warehouse}/areas/{warehouseArea}/edit', [WarehouseAreaController::class, 'editInWarehouse'])->name('show.areas.edit');


    Route::get('/{warehouse}/areas/{warehouseArea}/locations', [LocationController::class, 'indexInAreaInWarehouse'])->name('show.areas.show.locations.index');
    Route::get('/{warehouse}/areas/{warehouseArea}/locations/{location}', [LocationController::class, 'showInAreaInWarehouse'])->name('show.areas.show.locations.show');
    Route::get('/{warehouse}/areas/{warehouseArea}/locations/{location}/edit', [LocationController::class, 'editInAreaInWarehouse'])->name('show.areas.show.locations.edit');

});


