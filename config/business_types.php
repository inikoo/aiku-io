<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 20 Aug 2021 20:29:20 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */


return [

    'b2b'    => [

        'name'        => 'B2B commerce',
        'modules'     => [
            'dashboard' => [
                'route'    => 'dashboard.index',
                'name'     => 'Dashboard',
                'fa'       => ['fal', 'tachometer-alt-fast'],
                'sections' => []
            ],
            'hr'        => [
                'route'    => 'hr.index',
                'name'     => 'Staff',
                'fa'       => ['fal', 'clipboard-user'],
                'sections' => [
                    'hr.employees'  => [
                        'name' => 'Employees',
                    ],
                    'hr.timesheets' => [
                        'name' => 'Timesheets',
                    ],
                    'hr.logbook'    => [
                        'name' => 'Logbook',
                    ],

                ]
            ],
            'system'        => [
                'route'    => 'system.index',
                'name'     => 'system',
                'fa'       => ['fal', 'robot'],
                'sections' => [
                    'system.users'  => [
                        'name' => 'Users',
                    ],
                    'system.roles' => [
                        'name' => 'Roles',
                    ],

                    'system.billing' => [
                        'name' => 'Billing',
                    ],


                ]
            ],


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
            'dashboard' => [
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
            'hr'        => [
                'route'    => 'hr.index',
                'name'     => 'Staff',
                'fa'       => ['fal', 'clipboard-user'],
                'sections' => [
                    'hr.employees'  => [
                        'name' => 'Employees',
                    ],
                    'hr.timesheets' => [
                        'name' => 'Timesheets',
                    ],
                    'hr.logbook'    => [
                        'name' => 'Logbook',
                    ],

                ]
            ],
            'system'        => [
                'route'    => 'system.index',
                'name'     => 'system',
                'fa'       => ['fal', 'robot'],
                'sections' => [
                    'system.users'  => [
                        'name' => 'Users',
                    ],
                    'system.roles' => [
                        'name' => 'Roles',
                    ],

                    'system.billing' => [
                        'name' => 'Billing',
                    ],


                ]
            ],

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
