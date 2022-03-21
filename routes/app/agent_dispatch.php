<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 20 Mar 2022 00:57:56 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */


use App\Http\Controllers\Agent\AgentDispatchController;

Route::get('/', [AgentDispatchController::class, 'dashboard'])->name('dashboard');
Route::get('/clients', [AgentClientController::class, 'index'])->name('clients.index');
Route::get('/clients/{customer}', [AgentClientController::class, 'show'])->name('clients.show');
Route::get('/clients/{customer}/edit', [AgentClientController::class, 'edit'])->name('clients.edit');
Route::post('/clients/{customer}', [AgentClientController::class, 'update'])->name('clients.update');

Route::get('/orders', [AgentOrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{order}', [AgentOrderController::class, 'show'])->name('orders.show');
Route::get('/orders/{order}/edit', [AgentOrderController::class, 'edit'])->name('orders.edit');
Route::post('/orders/{order}', [AgentOrderController::class, 'update'])->name('orders.update');
