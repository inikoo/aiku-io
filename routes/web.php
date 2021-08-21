<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
    ]);
});



Route::middleware(['auth:sanctum', 'verified'])->get('/customers', function () {
    return Inertia::render('Customers');
})->name('customers');


Route::prefix('dashboard')->name('dashboard.')
    ->middleware(['auth:sanctum', 'verified'])
    ->group(__DIR__ . '/web/dashboard.php');

Route::prefix('patients')->name('patients.')
    ->middleware(['auth:sanctum', 'verified'])
    ->group(__DIR__ . '/web/patients.php');


Route::prefix('hr')->name('hr.')
    ->middleware(['auth:sanctum', 'verified'])
    ->group(__DIR__ . '/web/hr.php');


Route::prefix('system')->name('system.')
    ->middleware(['auth:sanctum', 'verified'])
    ->group(__DIR__ . '/web/system.php');
