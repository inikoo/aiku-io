<?php

use App\Actions\HumanResources\Employee\ShowEmployee;
use App\Actions\HumanResources\Employee\ShowEmployees;
use App\Actions\HumanResources\Employee\StoreEmployee;
use App\Actions\HumanResources\Employee\UpdateEmployee;

use App\Actions\System\Role\ShowRoles;
use App\Actions\System\User\ShowUser;
use App\Actions\System\User\ShowUsers;
use App\Actions\System\User\StoreUser;
use App\Actions\System\User\UpdateUser;
use Illuminate\Support\Facades\Route;


Route::get('/employees', ShowEmployees::class)->name('employees.index');
Route::post('/employees', StoreEmployee::class)->name('employees.store');
Route::get('/employees/{employee}',ShowEmployee::class)->name('employees.show');
Route::patch('/employees/{employee}',UpdateEmployee::class)->name('employees.update');

Route::get('/users', ShowUsers::class)->name('users.index');
Route::post('/users', StoreUser::class)->name('users.store');
Route::get('/users/{user}',ShowUser::class)->name('users.show');
Route::patch('/users/{user}',UpdateUser::class)->name('users.update');

Route::get('/roles', ShowRoles::class)->name('roles.index');
