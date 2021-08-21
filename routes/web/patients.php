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
        'Patients/Patients',
        [
            'title'       => __('Patients'),
            'breadcrumbs' => [
                'patients.index' => [
                    'name'    => __('Patients'),
                    'current' => true
                ],
            ],
            'actions'     => [
                [
                    'type'    => 'link',
                    'route'   => 'patients.new',
                    'name'    => __('New patient'),
                    'primary' => true
                ]
            ]
        ]
    );
})->name('index');

Route::get('/new', function () {
    return Inertia::render(
        'Patients/NewPatient',
        [
            'title'       => __('Patient registration'),
            'breadcrumbs' => [
                'patients.index' => [
                    'name'    => __('Patients'),
                    'current' => false
                ],
                'patients.new' => [
                    'name'    => __('New patient'),
                    'current' => true
                ],

            ],

        ]
    );
})->name('new');

