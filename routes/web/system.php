<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 21 Aug 2021 02:08:06 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render(
        'System/Index',
        [
            'title'       => __('System'),
            'breadcrumbs' => [
                'system.index' => [
                    'name'    => __('System'),
                    'current' => true
                ],
            ]
        ]
    );
})->name('index');

Route::middleware(['auth:sanctum', 'verified'])->get('/system/users', function () {
    return Inertia::render(
        'System/Users',
        [
            'title'       => __('Users'),
            'breadcrumbs' => [
                'system.index' => [
                    'name'    => __('System'),
                    'current' => false
                ],
                'system.users' => [
                    'name'    => __('Users'),
                    'current' => true
                ],
            ]
        ]
    );
})->name('users');

Route::middleware(['auth:sanctum', 'verified'])->get('/system/roles', function () {
    return Inertia::render('System/Roles',
                           [
                               'title'       => __('Roles'),
                               'breadcrumbs' => [
                                   'system.index' => [
                                       'name'    => __('System'),
                                       'current' => false
                                   ],
                                   'system.roles' => [
                                       'name'    => __('Roles'),
                                       'current' => true
                                   ],
                               ]
                           ]);
})->name('roles');

Route::middleware(['auth:sanctum', 'verified'])->get('/system/usage', function () {
    return Inertia::render('System/Usage',
                           [
                               'title'       => __('System usage'),
                               'breadcrumbs' => [
                                   'system.index' => [
                                       'name'    => __('System'),
                                       'current' => false
                                   ],
                                   'system.usage' => [
                                       'name'    => __('Usage'),
                                       'current' => true
                                   ],
                               ]
                           ]);
})->name('usage');

Route::middleware(['auth:sanctum', 'verified'])->get('/system/billing', function () {
    return Inertia::render('System/Billing',
                           [
                               'title'       => __('Billing'),
                               'breadcrumbs' => [
                                   'system.index' => [
                                       'name'    => __('System'),
                                       'current' => false
                                   ],
                                   'system.billing' => [
                                       'name'    => __('Billing'),
                                       'current' => true
                                   ],
                               ]
                           ]);
})->name('billing');



