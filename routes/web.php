<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
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



Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');


Route::get('/', function () {
    return redirect('/dashboard');
});

Route::prefix('dashboard')->name('dashboard.')
    ->middleware(['auth', 'verified'])
    ->group(__DIR__.'/web/dashboard.php');

/*
Route::prefix('patients')->name('patients.')
    ->middleware(['auth', 'verified'])
    ->group(__DIR__.'/web/patients.php');
*/

Route::prefix('human_resources')->name('human_resources.')
    ->middleware(['auth', 'verified'])
    ->group(__DIR__.'/web/human_resources.php');


Route::prefix('account')->name('account.')
    ->middleware(['auth', 'verified'])
    ->group(__DIR__.'/web/account.php');

Route::prefix('shops')->name('shops.')
    ->middleware(['auth', 'verified'])
    ->group(__DIR__.'/web/shops.php');

Route::prefix('fulfilment_houses')->name('fulfilment_houses.')
    ->middleware(['auth', 'verified'])
    ->group(__DIR__.'/web/fulfilment_houses.php');

Route::prefix('websites')->name('websites.')
    ->middleware(['auth', 'verified'])
    ->group(__DIR__.'/web/websites.php');

Route::prefix('inventory')->name('inventory.')
    ->middleware(['auth', 'verified'])
    ->group(__DIR__.'/web/inventory.php');


Route::prefix('warehouses')->name('warehouses.')
    ->middleware(['auth', 'verified'])
    ->group(__DIR__.'/web/warehouses.php');

Route::prefix('workshops')->name('workshops.')
    ->middleware(['auth', 'verified'])
    ->group(__DIR__.'/web/workshops.php');

Route::prefix('procurement')->name('procurement.')
    ->middleware(['auth', 'verified'])
    ->group(__DIR__.'/web/procurement.php');

Route::prefix('financials')->name('financials.')
    ->middleware(['auth', 'verified'])
    ->group(__DIR__.'/web/financials.php');

Route::middleware(['auth', 'verified'])->get('/profile', function () {
    return Inertia::render('Profile');
})->name('profile.show');



Route::prefix('profile')->name('profile.')
    ->middleware(['auth', 'verified'])
    ->group(__DIR__.'/web/profile.php');

