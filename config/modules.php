<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 20 Aug 2021 20:29:20 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */


return [

    'b2b' => [
        'dashboard' => [
            'route'    => 'dashboard.index',
            'name'     => 'Dashboard',
            'fa'       => ['far', 'tachometer-alt-fast'],
            'sections' => []
        ],
        'system'    => [
            'route'    => 'system.index',
            'name'     => 'System',
            'fa'       => ['far', 'robot'],
            'sections' => [
                'system.users'   => [
                    'name' => 'Users',
                ],
                'system.roles'   => [
                    'name' => 'Roles',
                ],
                'system.usage'   => [
                    'name' => 'Usage',
                ],
                'system.billing' => [
                    'name' => 'Billing',
                ]
            ]
        ],

    ]

];
