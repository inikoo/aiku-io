<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 15 Jan 2022 22:24:05 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

use App\Http\Controllers\Procurement\AgentController;
use App\Http\Controllers\Procurement\ProcurementController;
use App\Http\Controllers\Procurement\SupplierController;

Route::get('/', [ProcurementController::class, 'dashboard'])->name('dashboard');

Route::get('/agents', [AgentController::class, 'index'])->name('agents.index');
Route::get('/agents/{agent}', [AgentController::class, 'show'])->name('agents.show');
Route::get('/agents/{agent}/edit', [AgentController::class, 'edit'])->name('agents.edit');
Route::post('/agents/{agent}', [AgentController::class, 'update'])->name('agents.update');

Route::get('/agents/{agent}/suppliers', [SupplierController::class, 'indexInAgent'])->name('agents.show.suppliers.index');
Route::get('/agents/{agent}/purchase_orders', [PurchaseOrderController::class, 'indexInAgent'])->name('agents.show.purchase_orders.index');


Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
Route::get('/suppliers/{supplier}', [AgentController::class, 'show'])->name('suppliers.show');
Route::get('/suppliers/{supplier}/edit', [AgentController::class, 'edit'])->name('suppliers.edit');
Route::post('/suppliers/{supplier}', [AgentController::class, 'update'])->name('suppliers.update');

Route::get('/suppliers/{supplier}/purchase_orders', [PurchaseOrderController::class, 'indexInSupplier'])->name('suppliers.show.purchase_orders.index');


Route::get('/purchase_orders', [PurchaseOrderController::class, 'index'])->name('purchase_orders.index');
Route::get('/deliveries', [DeliveriesController::class, 'index'])->name('deliveries.index');
