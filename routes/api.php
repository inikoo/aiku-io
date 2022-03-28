<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 21 Oct 2021 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use App\Actions\Auth\Role\IndexRole;
use App\Actions\Auth\User\IndexUser;
use App\Actions\Auth\User\ShowUser;
use App\Actions\Auth\User\StoreUser;
use App\Actions\Auth\User\UpdateUser;
use Illuminate\Support\Facades\Route;

Route::prefix('employees')->name('api.employees.')
    ->group(__DIR__ . '/api/employees.php');

Route::get('/users', IndexUser::class)->name('api.users.index');
Route::post('/users', StoreUser::class)->name('api.users.store');
Route::get('/users/{user}',ShowUser::class)->name('api.users.show');
Route::patch('/users/{user}',UpdateUser::class)->name('api.users.update');

Route::get('/roles', IndexRole::class)->name('api.roles.index');
