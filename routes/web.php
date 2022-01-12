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
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});



Route::middleware(['auth', 'verified'])->get('/customers', function () {
    return Inertia::render('Customers');
})->name('customers');


Route::prefix('dashboard')->name('dashboard.')
    ->middleware(['auth', 'verified'])
    ->group(__DIR__ . '/web/dashboard.php');


Route::prefix('patients')->name('patients.')
    ->middleware(['auth', 'verified'])
    ->group(__DIR__ . '/web/patients.php');


Route::prefix('human_resources')->name('human_resources.')
    ->middleware(['auth', 'verified'])
    ->group(__DIR__.'/web/human_resources.php');


Route::prefix('system')->name('system.')
    ->middleware(['auth', 'verified'])
    ->group(__DIR__ . '/web/system.php');

Route::prefix('shops')->name('shops.')
    ->middleware(['auth', 'verified'])
    ->group(__DIR__ . '/web/shops.php');

Route::prefix('shop')->name('shop.')
    ->middleware(['auth', 'verified'])
    ->group(__DIR__ . '/web/shop.php');

Route::prefix('dropshippings')->name('dropshippings.')
    ->middleware(['auth', 'verified'])
    ->group(__DIR__ . '/web/dropshippings.php');


Route::middleware(['auth', 'verified'])->get('/profile', function () {
    return Inertia::render('Profile');
})->name('profile.show');


require __DIR__.'/auth.php';
