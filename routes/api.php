<?php

use App\Actions\HumanResources\Employee\ShowEmployee;
use App\Actions\HumanResources\Employee\ShowEmployees;
use App\Actions\HumanResources\Employee\StoreEmployee;
use App\Actions\HumanResources\Employee\UpdateEmployee;

use Illuminate\Support\Facades\Route;


Route::get('/employees', ShowEmployees::class)->name('employees.index');
Route::post('/employees', StoreEmployee::class)->name('employees.store');
Route::get('/employees/{employee}',ShowEmployee::class)->name('employees.show');
Route::patch('/employees/{employee}',UpdateEmployee::class)->name('employees.update');


