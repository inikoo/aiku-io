<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 07 Sep 2021 20:20:13 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use App\Http\Controllers\Aiku\DeploymentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/deployment/last', [DeploymentController::class, 'last'])->name('deployment.last');


Route::middleware('auth:sanctum')->get('/deployment/create', [DeploymentController::class, 'store'])->name('deployment.store');

