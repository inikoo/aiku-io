<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 03 Feb 2022 18:34:27 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

return [
    'dashboard'       => [
        'route'    => 'dashboard.index',
        'name'     => 'Dashboard',
        'icon'     => ['fal', 'tachometer-alt-fast'],
        'sections' => []
    ],
    'patients'        => [
        'route'    => 'patients.index',
        'name'     => 'Patients',
        'icon'     => ['fal', 'users'],
        'sections' => [


        ]
    ],
    'human_resources' => [
        'id'          => 'human_resources',
        'type'        => 'standard',
        'route'       => 'human_resources.dashboard',
        'permissions' => ['employees.view'],
        'name'        => 'Human resources',
        'code'        => 'HR',
        'icon'        => ['fal', 'clipboard-user'],
        'sections'    => [
            'human_resources.employees.index'  => [
                'name' => 'Employees',
            ],
            'human_resources.timesheets.index' => [
                'name' => 'Timesheets',
            ],
            'human_resources.logbook'          => [
                'name' => 'Logbook',
            ],

        ]
    ],
    'account'         => [
        'id'          => 'account',
        'type'        => 'standard',
        'route'       => 'account.show',
        'permissions' => ['account.users.view'],
        'name'        => 'Account',
        'code'        => 'Acc',

        'icon'     => ['fal', 'dice-d4'],
        'sections' => [

            'account.users.index'  => [
                'name' => 'Users',
            ],
            'account.guests.index' => [
                'name' => 'Guests',
            ],
            'account.roles.index'  => [
                'name' => 'Roles',
            ],

            'account.billing' => [
                'name' => 'Billing',
            ],


        ]
    ]

];
