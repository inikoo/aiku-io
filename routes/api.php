<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 21 Oct 2021 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */



use App\Actions\System\Role\ShowRoles;
use App\Actions\System\User\ShowUser;
use App\Actions\System\User\IndexUser;
use App\Actions\System\User\StoreUser;
use App\Actions\System\User\UpdateUser;
use Illuminate\Support\Facades\Route;


Route::prefix('employees')->name('employees.')
    ->group(__DIR__ . '/api/employees.php');




Route::get('/users', IndexUser::class)->name('users.index');
Route::post('/users', StoreUser::class)->name('users.store');
Route::get('/users/{user}',ShowUser::class)->name('users.show');
Route::patch('/users/{user}',UpdateUser::class)->name('users.update');

Route::get('/roles', ShowRoles::class)->name('roles.index');
