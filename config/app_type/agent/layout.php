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
        'code'      => 'agent_dispatch',
        'route'     => 'agent_dispatch.dashboard',
        'name'      => 'Dispatch',
        'shortName' => 'Dispatch',
        'icon'      => ['fal', 'store'],
        'sections'  => [
            'agent_dispatch.clients.index'       => [
                'name' => 'Clients',
                'icon' => ['fal', 'user'],
            ],
            'agent_dispatch.orders.index'       => [
                'name' => 'Orders',
                'icon' => ['fal', 'clipboard'],
            ],
        ]
    ],
    [
        'code'      => 'inventory',
        'route'     => 'inventory.dashboard',
        'name'      => 'Inventory',
        'shortName' => 'Inventory',
        'icon'      => ['fal', 'box'],
        'sections'  => [
            'inventory.stocks.index'          => [
                'name' => 'Stocks',
                'icon' => ['fal', 'box'],
            ],
            'inventory.stock_locations.index' => [
                'name' => 'Stock-Locations',
            ],

        ]
    ],
    [
        'code'      => 'procurement',
        'route'     => 'procurement.dashboard',
        'name'      => 'Procurement',
        'shortName' => 'Procurement',
        'icon'      => ['fal', 'apple-crate'],
        'sections'  => [

            'procurement.suppliers.index'       => [
                'name' => 'Suppliers',
                'icon' => ['fal', 'hand-holding-box'],
            ],
            'procurement.purchase_orders.index' => [
                'name' => 'Purchase orders',
            ],
            'procurement.deliveries.index'      => [
                'name' => 'Deliveries',
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



        ]
    ]


];

