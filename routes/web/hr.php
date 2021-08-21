<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 21 Aug 2021 04:40:33 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render(
        'HumanResources/Index',
        [
            'title'       => __('Human resources'),
            'breadcrumbs' => [
                'hr.index' => [
                    'name'    => __('Human resources'),
                    'current' => true
                ],
            ]
        ]
    );
})->name('index');

Route::middleware(['auth:sanctum', 'verified'])->get('/employees', function () {
    return Inertia::render(
        'HumanResources/Employees',
        [
            'title'       => __('Employees'),
            'breadcrumbs' => [
                'hr.index' => [
                    'name'    => __('Human resources'),
                    'current' => false
                ],
                'hr.employees' => [
                    'name'    => __('Employees'),
                    'current' => true
                ],
            ]
        ]
    );
})->name('employees');

Route::middleware(['auth:sanctum', 'verified'])->get('/timesheets', function () {
    return Inertia::render(
        'HumanResources/Employees',
        [
            'title'       => __('Timesheets'),
            'breadcrumbs' => [
                'hr.index' => [
                    'name'    => __('Human resources'),
                    'current' => false
                ],
                'hr.timesheets' => [
                    'name'    => __('Timesheets'),
                    'current' => true
                ],
            ]
        ]
    );
})->name('timesheets');

Route::middleware(['auth:sanctum', 'verified'])->get('/logbook', function () {
    return Inertia::render(
        'HumanResources/Employees',
        [
            'title'       => __('Logbook'),
            'breadcrumbs' => [
                'hr.index' => [
                    'name'    => __('Human resources'),
                    'current' => false
                ],
                'hr.logbook' => [
                    'name'    => __('Logbook'),
                    'current' => true
                ],
            ]
        ]
    );
})->name('logbook');

