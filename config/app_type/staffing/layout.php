<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 03 Feb 2022 18:34:27 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */


return [
    [
        'code'      => 'dashboard',
        'route'     => 'dashboard.index',
        'name'      => 'Home',
        'shortName' => 'Home',
        'icon'      => ['fal', 'tachometer-alt-fast'],
        'sections'  => []
    ],

    [
        'code'      => 'staffing',
        'route'     => 'staffing.dashboard',
        'name'      => 'Staffing',
        'shortName' => 'Staffing',
        'icon'      => ['fal', 'box'],
        'sections'  => [
            'staffing.dashboard'          => [
                'name' => 'Dashboard',
                'icon' => ['fal', 'tachometer-alt-fast'],
            ],
            'staffing.recruiters.index'          => [
                'name' => 'Recruiters',
                'icon' => ['fal', 'user-secret'],
            ],
            'staffing.applicants.index'          => [
                'name' => 'Applicants',
                'icon' => ['fal', 'user'],
            ],

        ]
    ],

    [
        'code'        => 'financials',
        'route'       => 'financials.dashboard',
        'permissions' => ['financials.view'],
        'name'        => 'Financials',
        'shortName'   => 'F$',
        'icon'        => ['fal', 'abacus'],
        'sections'    => [
        ]
    ],
    [
        'code'        => 'human_resources',
        'route'       => 'human_resources.dashboard',
        'permissions' => ['employees.view'],
        'name'        => 'Human Resources',
        'shortName'   => 'HR',
        'icon'        => ['fal', 'clipboard-user'],
        'sections'    => [
            'human_resources.employees.index'  => [
                'name' => 'Employees',
                'icon' => ['fal', 'user-hard-hat'],
            ],
            'human_resources.working_hours.interval' => [
                'name' => 'Working hours',
                'icon' => ['fal', 'business-time'],
            ],
            'human_resources.timesheets.index' => [
                'name' => 'Timesheets',
                'icon' => ['fal', 'chess-clock'],
            ],


        ]
    ],
    [
        'code'      => 'reports',
        'route'     => 'reports.dashboard',
        'name'      => 'Reports',
        'shortName' => 'Reports',
        'icon'      => ['fal', 'chart-line'],
        'sections'  => [
        ]
    ],
    'account' => [
        'code'        => 'account',
        'route'       => 'account.show',
        'permissions' => ['account.users.view'],
        'name'        => 'Account',
        'shortName'   => 'Acc',

        'icon'     => ['fal', 'dice-d4'],
        'sections' => [

            'account.users.index'  => [
                'name' => 'Users',
                'icon' => ['fal', 'user-circle'],

            ],
            'account.guests.index' => [
                'name' => 'Guests',
                'icon' => ['fal', 'user-alien'],

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

