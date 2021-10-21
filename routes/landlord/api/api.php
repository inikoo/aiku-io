<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 07 Sep 2021 20:20:13 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use App\Actions\Account\Tenant\ShowTenant;
use App\Actions\Account\Tenant\ShowTenants;
use App\Actions\Account\Tenant\StoreTenant;
use App\Actions\Account\Tenant\UpdateTenant;
use App\Http\Controllers\Aiku\DeploymentController;
use Illuminate\Support\Facades\Route;

Route::get('/deployments/latest', [DeploymentController::class, 'latest'])->name('deployments.latest');
Route::get('/deployments/{deploymentId}', [DeploymentController::class, 'show'])->name('deployments.show');
Route::post('/deployments/create', [DeploymentController::class, 'store'])->name('deployments.store');
Route::post('/deployments/latest/edit', [DeploymentController::class, 'updateLatest'])->name('deployments.edit.latest');


Route::get('/tenants', ShowTenants::class)->name('tenants.index');
Route::post('/tenants', StoreTenant::class)->name('tenants.store');
Route::get('/tenants/{tenant}',ShowTenant::class)->name('tenants.show');
Route::patch('/tenants/{tenant}',UpdateTenant::class)->name('tenants.update');
