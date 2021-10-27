<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 22 Oct 2021 19:58:16 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */


use App\Actions\Migrations\MigrateCustomer;
use App\Actions\Migrations\MigrateEmployee;
use App\Actions\Migrations\MigrateShop;
use Illuminate\Support\Facades\Route;


Route::post('/shop/{aurora_id}', MigrateShop::class)->name('migrate.shop');
Route::post('/customer/{aurora_id}', MigrateCustomer::class)->name('migrate.customer');
Route::post('/employee/{aurora_id}', MigrateEmployee::class)->name('migrate.employee');
