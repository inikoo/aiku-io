<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 23 Feb 2022 22:23:28 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

use App\Http\Controllers\Procurement\AgentController;
use App\Http\Controllers\Procurement\DeliveriesController;
use App\Http\Controllers\Procurement\ProcurementController;
use App\Http\Controllers\Procurement\PurchaseOrderController;
use App\Http\Controllers\Procurement\SupplierController;
use App\Http\Controllers\Procurement\SupplierProductController;

Route::get('/', [ProcurementController::class, 'dashboard'])->name('dashboard');

Route::get('/agents', [AgentController::class, 'index'])->name('agents.index');
Route::get('/agents/{agent}', [AgentController::class, 'show'])->name('agents.show');
Route::get('/agents/{agent}/edit', [AgentController::class, 'edit'])->name('agents.edit');
Route::post('/agents/{agent}', [AgentController::class, 'update'])->name('agents.update');

Route::get('/agents/{agent}/suppliers', [SupplierController::class, 'indexInAgent'])->name('agents.show.suppliers.index');
Route::scopeBindings()->group(function () {
    Route::get('/agents/{agent}/suppliers/{supplier}', [SupplierController::class, 'showInAgent'])->name('agents.show.suppliers.show')->scopeBindings();
    Route::get('/agents/{agent}/suppliers/{supplier}/edit', [SupplierController::class, 'editInAgent'])->name('agents.show.suppliers.edit')->scopeBindings();
    Route::post('/agents/{agent}/suppliers/{supplier}', [SupplierController::class, 'updateInAgent'])->name('agents.show.suppliers.update')->scopeBindings();


    Route::get('/agents/{agent}/suppliers/{supplier}/purchase_orders', [PurchaseOrderController::class, 'indexInSupplierInAgent'])->name('agents.show.suppliers.show.purchase_orders.index')->scopeBindings();
    Route::get('/agents/{agent}/suppliers/{supplier}/purchase_orders/{purchaseOrder}', [PurchaseOrderController::class, 'showInSupplierInAgent'])->name('agents.show.suppliers.show.purchase_orders.show')->scopeBindings();
});

Route::get('/agents/{agent}/purchase_orders', [PurchaseOrderController::class, 'indexInAgent'])->name('agents.show.purchase_orders.index');


Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
Route::get('/suppliers/create', [SupplierController::class, 'createInTenant'])->name('suppliers.create');
Route::post('/suppliers/create', [SupplierController::class, 'store'])->name('suppliers.store');

Route::get('/suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');
Route::get('/suppliers/{supplier}/edit', [AgentController::class, 'edit'])->name('suppliers.edit');
Route::post('/suppliers/{supplier}', [AgentController::class, 'update'])->name('suppliers.update');

Route::scopeBindings()->group(function () {
    Route::get('/suppliers/{supplier}/products', [SupplierProductController::class, 'indexInSupplier'])->name('suppliers.show.products.index');
    Route::get('/suppliers/{supplier}/products/{supplierProduct}', [SupplierProductController::class, 'showInSupplier'])->name('suppliers.show.products.show');

    Route::get('/suppliers/{supplier}/purchase_orders', [PurchaseOrderController::class, 'indexInSupplier'])->name('suppliers.show.purchase_orders.index');
});

Route::get('/products', [SupplierProductController::class, 'indexInTenant'])->name('products.index');
Route::get('/products/availability/{availabilityStatus}', [SupplierProductController::class, 'indexInTenantWithAvailabilityStatus'])->name('products.stock_quantity_status');
Route::get('/products/{supplierProduct}', [SupplierProductController::class, 'showInTenant'])->name('products.show');
Route::get('/products/{supplierProduct}/edit', [SupplierProductController::class, 'edit'])->name('products.edit');
Route::post('/products/{supplierProduct}', [SupplierProductController::class, 'update'])->name('products.update');
Route::get('/products', [SupplierProductController::class, 'indexInTenant'])->name('products.index');


Route::get('/purchase_orders', [PurchaseOrderController::class, 'indexInTenant'])->name('purchase_orders.index');
Route::get('/purchase_orders/state/{purchaseOrderState}', [PurchaseOrderController::class, 'indexInTenantWithState'])->name('purchase_orders.state');
Route::get('/purchase_orders/{purchaseOrder}', [PurchaseOrderController::class, 'showInTenant'])->name('purchase_orders.show');
Route::get('/purchase_orders/{purchaseOrder}/edit', [PurchaseOrderController::class, 'edit'])->name('purchase_orders.edit');
Route::post('/purchase_orders/{purchaseOrder}', [PurchaseOrderController::class, 'update'])->name('purchase_orders.update');

Route::get('/deliveries', [DeliveriesController::class, 'index'])->name('deliveries.index');
