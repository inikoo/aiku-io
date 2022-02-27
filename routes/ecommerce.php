<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 24 Feb 2022 15:59:37 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest:ecommerce')
    ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest:ecommerce')->defaults('guard','ecommerce');

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware(['auth:ecommerce'])->group(function () {

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout')->defaults('guard','ecommerce');

    Route::prefix('dashboard')->name('dashboard.')
        ->group(__DIR__.'/web/dashboard.php');

    Route::prefix('human_resources')->name('human_resources.')
        ->group(__DIR__.'/web/human_resources.php');

    Route::prefix('account')->name('account.')
        ->group(__DIR__.'/web/account.php');

    Route::prefix('marketing')->name('marketing.')
        ->group(__DIR__.'/web/marketing.php');

    Route::prefix('websites')->name('websites.')
        ->group(__DIR__.'/web/websites.php');

    Route::prefix('inventory')->name('inventory.')
        ->group(__DIR__.'/web/inventory.php');

    Route::prefix('warehouses')->name('warehouses.')
        ->group(__DIR__.'/web/warehouses.php');

    Route::prefix('workshops')->name('workshops.')
        ->group(__DIR__.'/web/workshops.php');

    Route::prefix('procurement')->name('procurement.')
        ->group(__DIR__.'/web/procurement.php');

    Route::prefix('financials')->name('financials.')
        ->group(__DIR__.'/web/financials.php');

    Route::prefix('reports')->name('reports.')
        ->group(__DIR__.'/web/reports.php');

    Route::middleware(['auth'])->get('/profile', function () {
    })->name('profile.show');

    Route::prefix('profile')->name('profile.')
        ->group(__DIR__.'/web/profile.php');

});



