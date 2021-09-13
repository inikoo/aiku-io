<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 20 Aug 2021 20:29:20 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */


$human_resources = [
    'route'    => 'human_resources.index',
    'name'     => 'Staff',
    'fa'       => ['fal', 'clipboard-user'],
    'sections' => [
        'human_resources.employees.index' => [
            'name' => 'Employees',
        ],
        'human_resources.timesheets.index'      => [
            'name' => 'Timesheets',
        ],
        'human_resources.logbook'         => [
            'name' => 'Logbook',
        ],

    ]
];

$system=[
    'route'    => 'system.index',
    'name'     => 'system',
    'fa'       => ['fal', 'robot'],
    'sections' => [
        'system.users' => [
            'name' => 'Users',
        ],
        'system.roles' => [
            'name' => 'Roles',
        ],

        'system.billing' => [
            'name' => 'Billing',
        ],


    ]
];


return [

    'b2b'    => [

        'name'        => 'B2B commerce',
        'modules'     => [
            'dashboard'       => [
                'route'    => 'dashboard.index',
                'name'     => 'Dashboard',
                'fa'       => ['fal', 'tachometer-alt-fast'],
                'sections' => []
            ],
            'human_resources' => $human_resources,
            'system'          => $system



        ],
        'permissions' => [
            'users.create',
            'users.edit',
            'users.delete',
            'users.*',
            'look-and-field',
            'employees.edit',
            'employees.delete',
            'employees.payroll',
            'employees.confidential',
            'employees.attendance',
            'employees.*'
        ],
        'roles'       => [

            'super-admin'            => [],
            'system-admin'           => [
                'users.*',
                'look-and-field',
            ],
            'human-resources-clerk'  => [],
            'human-resources-admin*' => [],

        ]
    ],
    'health' => [
        'name'        => 'Health',
        'modules'     => [
            'dashboard'       => [
                'route'    => 'dashboard.index',
                'name'     => 'Dashboard',
                'fa'       => ['fal', 'tachometer-alt-fast'],
                'sections' => []
            ],
            'patients'        => [
                'route'    => 'patients.index',
                'name'     => 'Patients',
                'fa'       => ['fal', 'users'],
                'sections' => [


                ]
            ],
            'human_resources' => $human_resources,
            'system'          => $system


        ],
        'permissions' => [
            'users.create',
            'users.edit',
            'users.delete',
            'users.*',
            'look-and-field',
            'employees.edit',
            'employees.delete',
            'employees.payroll',
            'employees.confidential',
            'employees.attendance',
            'employees.*'
        ],
        'roles'       => [

            'super-admin'            => [],
            'system-admin'           => [
                'users.*',
                'look-and-field',
            ],
            'human-resources-clerk'  => [],
            'human-resources-admin*' => [],

        ]
    ]

];
