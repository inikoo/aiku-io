<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 03 Feb 2022 18:34:27 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

return [

    'dashboard' => [
        'id'       => 'dashboard',
        'type'     => 'home',
        'route'    => 'dashboard.index',
        'name'     => 'Dashboard',
        'code'     => 'Dashboard',
        'icon'     => ['fal', 'tachometer-alt-fast'],
        'sections' => []
    ],
    'profile'   => [
        'id'       => 'profile',
        'type'     => 'profile',
        'route'    => 'profile.show',
        'name'     => 'Profile',
        'code'     => 'Profile',
        'sections' => [
            'profile.roles.index' => [
                'name' => 'Roles',
            ],
        ]
    ],

    'ecommerce_shops' => [
        'id'           => 'ecommerce_shops',
        'bgColor'      => 'pink',
        'type'         => 'modelIndex',
        'with_options' => true,
        'route'        => 'ecommerce_shops.index',
        'permissions'  => ['shops.view'],
        'name'         => 'Ecommerce shops',
        'code'         => 'Shops',
        'icon'         => ['fal', 'store-alt'],
        'sections'     => [
            'ecommerce_shops.customers.index' => [
                'name' => 'Customers',
                'icon' => ['fal', 'layer-group'],
            ],
            'ecommerce_shops.orders.index'    => [
                'name' => 'Orders',
                'icon' => ['fal', 'layer-group'],
            ],


        ]
    ],
    'ecommerce_shop'  => [
        'id'         => 'ecommerce_shop',
        'bgColor'    => 'pink',
        'modelIndex' => 'ecommerce_shops.index',
        'icon'       => ['fal', 'store-alt'],
        'type'       => 'modelOptions',
        'name'       => 'Ecommerce shop',
        'code'       => 'Shop',
        'sections'   => [
            'ecommerce_shops.show.customers.index' => [
                'name' => 'Customers',
            ],
            'ecommerce_shops.show.orders.index'    => [
                'name' => 'Orders',
            ],
        ]

    ],


    'fulfilment_houses' => [
        'id'          => 'fulfilment_houses',
        'type'        => 'modelIndex',
        'route'       => 'fulfilment_houses.index',
        'permissions' => ['shops.view'],
        'name'        => 'Fulfilment houses',
        'code'        => 'FHs',
        'icon'        => ['fal', 'person-carry'],
        'sections'    => [


        ]
    ],
    'fulfilment_house'  => [
        'id'         => 'fulfilment_house',
        'modelIndex' => 'fulfilment_houses.index',

        'icon' => ['fal', 'person-carry'],
        'type' => 'modelOptions',
        'name' => 'Fulfilment',
        'code' => 'FH',

    ],

    'websites' => [
        'id' => 'websites',


        'type'         => 'modelIndex',
        'with_options' => true,
        'route'        => 'websites.index',
        'permissions'  => ['websites.view'],
        'name'         => 'Websites',
        'code'         => 'Webs',
        'icon'         => ['fal', 'globe'],


    ],
    'website'  => [
        'id'         => 'website',
        'modelIndex' => 'websites.index',
        'icon'       => ['fal', 'globe'],
        'type'       => 'modelOptions',
        'name'       => 'Website',
        'code'       => 'Web',

    ],

    'inventory' => [
        'id'          => 'inventory',
        'type'        => 'standard',
        'route'       => 'inventory.dashboard',
        'permissions' => ['inventory.view'],
        'name'        => 'Inventory',
        'code'        => 'Inv',
        'icon'        => ['fal', 'box'],
        'sections'    => [
            'inventory.stocks.index'          => [
                'name' => 'Stocks',
            ],
            'inventory.stock_locations.index' => [
                'name' => 'Stock-Locations',
            ],

        ]
    ],

    'warehouses' => [
        'id'           => 'warehouses',
        'type'         => 'modelIndex',
        'with_options' => true,
        'route'        => 'warehouses.index',
        'permissions'  => ['warehouses.view'],
        'name'         => 'Warehouses',
        'code'         => 'WHs',
        'icon'         => ['fal', 'warehouse-alt'],
        'sections'     => [
            'warehouses.locations.index' => [
                'name' => 'Locations',
                'icon' => ['fal', 'layer-group'],
            ],
            'warehouses.areas.index'     => [
                'name' => 'WH Areas',
                'icon' => ['fal', 'layer-group'],
            ],
        ]

    ],
    'warehouse'  => [
        'id'         => 'warehouse',
        'modelIndex' => 'warehouses.index',
        'icon'       => ['fal', 'warehouse-alt'],
        'type'       => 'modelOptions',
        'name'       => 'Warehouse',
        'code'       => 'WH',
        'sections'   => [

            'warehouses.show.locations.index' => [
                'name' => 'Locations',
            ],
            'warehouses.show.areas.index'     => [
                'name' => 'WH Areas',
            ],
        ]
    ],

    'workshops' => [
        'id'           => 'workshops',
        'type'         => 'modelIndex',
        'with_options' => true,
        'route'        => 'workshops.index',
        'permissions'  => ['workshops.view'],
        'name'         => 'Workshops',
        'code'         => 'WS',
        'icon'         => ['fal', 'industry'],


    ],
    'workshop'  => [
        'id'         => 'workshop',
        'modelIndex' => 'workshop.index',
        'icon'       => ['fal', 'industry'],
        'type'       => 'modelOptions',
        'name'       => 'Workshop',
        'code'       => 'WS',

    ],

    'procurement' => [
        'id'          => 'procurement',
        'type'        => 'standard',
        'route'       => 'procurement.dashboard',
        'permissions' => ['procurement.view'],
        'name'        => 'Procurement',
        'code'        => 'Buy',
        'icon'        => ['fal', 'apple-crate'],
        'sections'    => [
            'procurement.agents.index'          => [
                'name' => 'Agents',
            ],
            'procurement.suppliers.index'       => [
                'name' => 'Suppliers',
            ],
            'procurement.purchase_orders.index' => [
                'name' => 'Purchase orders',
            ],
            'procurement.deliveries.index'      => [
                'name' => 'Deliveries',
            ],
        ]
    ],
    'financials'  => [
        'id' => 'financials',

        'type'        => 'standard',
        'route'       => 'financials.dashboard',
        'permissions' => ['financials.view'],
        'name'        => 'Financials',
        'code'        => 'F$',
        'icon'        => ['fal', 'abacus'],
        'sections'    => [
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
