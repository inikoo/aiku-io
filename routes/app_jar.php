<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 28 Feb 2022 01:47:38 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

use App\Http\Controllers\Auth\JarAuthenticatedSessionController;
use Illuminate\Support\Facades\Route;


Route::get('/login', [JarAuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('jar.login');

Route::post('/login', [JarAuthenticatedSessionController::class, 'setTenant'])
    ->middleware('guest');

Route::get('/proxy_login/{token}', [JarAuthenticatedSessionController::class, 'proxyStore'])
    ->middleware('guest')->name('jar.proxy_login');

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::post('/logout', [JarAuthenticatedSessionController::class, 'destroy'])
    ->name('jar.logout')->middleware(['auth']);


//Route::middleware(['auth'])->group(__DIR__.'/app/root.php');



