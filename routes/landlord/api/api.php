<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 07 Sep 2021 20:20:13 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use App\Http\Controllers\Aiku\DeploymentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/deployments/latest', [DeploymentController::class, 'latest'])->name('deployment.latest');
Route::middleware('auth:sanctum')->get('/deployments/{deploymentId}', [DeploymentController::class, 'show'])->name('deployment.show');
Route::middleware('auth:sanctum')->post('/deployments/create', [DeploymentController::class, 'store'])->name('deployment.store');

