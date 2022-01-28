<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 20 Aug 2021 20:29:20 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */


$human_resources = [
    'id'          => 'human_resources',
    'type'        => 'standard',
    'route'       => 'human_resources.index',
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
];

$account = [
    'id'          => 'account',
    'type'        => 'standard',
    'route'       => 'account.show',
    'permissions' => ['account.users.view'],
    'name'        => 'Account',
    'code'        => 'Acc',

    'icon'     => ['fal', 'dice-d4'],
    'sections' => [

        'account.users.index' => [
            'name' => 'Users',
        ],
        'account.guests.index' => [
            'name' => 'Guests',
        ],
        'account.roles.index' => [
            'name' => 'Roles',
        ],

        'account.billing' => [
            'name' => 'Billing',
        ],


    ]
];


return [

    'b2b'    => [

        'name'              => 'B2B commerce',
        'modules'           => [

            'dashboard' => [
                'id'       => 'dashboard',
                'type'     => 'home',
                'route'    => 'dashboard.index',
                'name'     => 'Dashboard',
                'code'     => 'Dashboard',
                'icon'     => ['fal', 'tachometer-alt-fast'],
                'sections' => []
            ],
            'profile' => [
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
                    'inventory.stocks.index'                    => [
                        'name' => 'Stocks',
                    ],
                    'inventory.warehouses.show.locations.index' => [
                        'name' => 'Locations',
                    ],
                    'inventory.warehouses.show.areas.index'     => [
                        'name' => 'WH Areas',
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


            'human_resources' => $human_resources,
            'account'         => $account


        ],
        'permissions'       => [
            'account',
            'account.edit',
            'account.users.view',
            'account.users.create',
            'account.users.edit',
            'account.users.delete',
            'account.users',
            'account.look-and-field',

            'employees.view',
            'employees.edit',
            'employees.delete',
            'employees.payroll',
            'employees.confidential',
            'employees.attendance',
            'employees',


            'procurement',
            'procurement.agents.view',
            'procurement.agents.edit',
            'procurement.agents.delete',
            'procurement.agents',
            'procurement.suppliers.view',
            'procurement.suppliers.edit',
            'procurement.suppliers.delete',
            'procurement.suppliers',

            'shops',
            'shops.view',
            'shops.edit',
            'shops.delete',

            'shops.products',
            'shops.products.view',
            'shops.products.edit',
            'shops.products.delete',

            'shops.customers',
            'shops.customers.view',
            'shops.customers.edit',
            'shops.customers.delete',

            'shops.broadcasting',
            'shops.broadcasting.view',
            'shops.broadcasting.edit',
            'shops.broadcasting.send',
            'shops.broadcasting.delete',


            'websites',
            'websites.view',
            'websites.edit',
            'websites.publish',
            'websites.delete',


            'financials',
            'financials.view',
            'financials.edit',
            'financials.delete',

            'financials.accounts_receivable',
            'financials.accounts_receivable.view',
            'financials.accounts_receivable.edit',
            'financials.accounts_receivable.delete',

            'financials.accounts_payable',
            'financials.accounts_payable.view',
            'financials.accounts_payable.edit',
            'financials.accounts_payable.delete',


            'inventory',
            'inventory.stocks',
            'inventory.stocks.view',
            'inventory.stocks.edit',
            'inventory.stocks.delete',


            'warehouses.view',
            'warehouses.edit',
            'warehouses.delete',
            'warehouses.stock',
            'warehouses.lost_stock',
            'warehouses.dispatching',
            'warehouses.dispatching.pick',
            'warehouses.dispatching.pack',

            'warehouses',


            'workshops.view',
            'workshops.edit',
            'workshops.dispatcher',
            'workshops.stock',
            'workshops.delete',
            'workshops',


        ],
        'model_permissions' => [
            'Shop' => [
                'shops.#',
                'shops.#.view',
                'shops.#.edit',
                'shops.#.delete',

                'shops.#.products',
                'shops.#.products.view',
                'shops.#.products.edit',
                'shops.#.products.delete',

                'shops.#.customers',
                'shops.#.customers.view',
                'shops.#.customers.edit',
                'shops.#.customers.delete',

                'shops.#.broadcasting',
                'shops.#.broadcasting.view',
                'shops.#.broadcasting.edit',
                'shops.#.broadcasting.send',
                'shops.#.broadcasting.delete',

                'shops.#.website',
                'shops.#.website.view',
                'shops.#.website.edit',
                'shops.#.website.publish',
                'shops.#.website.delete',


            ],

            'Website' => [


                'websites.#',
                'websites.#.view',
                'websites.#.edit',
                'websites.#.publish',
                'websites.#.delete',


            ],


            'Warehouse' => [
                'warehouse.#',
                'warehouse.#.view',
                'warehouse.#.edit',
                'warehouse.#.delete',
                'warehouse.#.stock',
                'warehouse.#.lost_stock',
                'warehouse.#.dispatching',
                'warehouse.#.dispatching.pick',
                'warehouse.#.dispatching.pack',
            ],
            'Workshop'  => [
                'workshop.#',
                'workshop.#.view',
                'workshop.#.stock',
                'workshop.#.dispatcher',
                'workshop.#.edit',
                'workshop.#.delete',
            ]

        ],


        'job_positions' => [
            [
                'slug'  => 'dir',
                'name'  => 'director',
                'roles' => [

                ]
            ],
            [
                'slug'  => 'hr-m',
                'name'  => 'Human resources supervisor',
                'roles' => [
                    'human-resources-admin'
                ]
            ],
            [
                'slug'  => 'hr',
                'name'  => 'Human resources',
                'roles' => [
                    'human-resources-clerk'
                ]
            ],
            [
                'slug'  => 'acc',
                'name'  => 'Accounts',
                'roles' => [

                ]
            ],
            [
                'slug'  => 'mrk',
                'name'  => 'Marketing',
                'roles' => [

                ]
            ],
            [
                'slug'  => 'web',
                'name'  => 'Web designer',
                'roles' => [

                ]
            ],
            [
                'slug'  => 'buy',
                'name'  => 'Buyer',
                'roles' => [

                ]
            ],
            [
                'slug'  => 'wah-m',
                'name'  => 'Warehouse supervisor',
                'roles' => [

                ]
            ],
            [
                'slug'  => 'wah-sk',
                'name'  => 'Warehouse stock keeper',
                'roles' => [

                ]
            ],
            [
                'slug'  => 'wah-sc',
                'name'  => 'Stock Controller',
                'roles' => [

                ]
            ],
            [
                'slug'  => 'dist-m',
                'name'  => 'Dispatch supervisor',
                'roles' => [

                ]
            ],
            [
                'slug'  => 'dist-pik',
                'name'  => 'Picker',
                'roles' => [

                ]
            ],
            [
                'slug'  => 'dist-pak',
                'name'  => 'Packer',
                'roles' => [

                ]
            ],
            [
                'slug'  => 'prod-m',
                'name'  => 'Production supervisor',
                'roles' => [

                ]
            ],
            [
                'slug'  => 'prod-w',
                'name'  => 'Production operative',
                'roles' => [

                ]
            ],
            [
                'slug'  => 'cus-m',
                'name'  => 'Customer service supervisor',
                'roles' => [

                ]
            ],
            [
                'slug'  => 'cus',
                'name'  => 'Customer service',
                'roles' => [

                ]
            ],
        ],


        'roles'       => [

            'super-admin'           => [
                'account',
                'employees',
                'procurement',
                'workshops',
                'shops',
                'websites',
                'inventory',
                'warehouses',
                'financials',
            ],
            'system-admin'          => [
                'account.users',
                'account.look-and-field',
            ],
            'human-resources-clerk' => [
                'employees.view',
                'employees.edit',
                'employees.payroll',
                'employees.attendance',
            ],
            'human-resources-admin' => [
                'employees',
            ],
            'buyer-clerk'           => [
                'procurement.agents.view',
                'procurement.agents.edit',
                'procurement.suppliers.view',
                'procurement.suppliers.edit',
            ],
            'buyer-admin'           => [
                'procurement',
            ],
            'workshop-operative'   => [
                'workshops.view',
            ],
            'workshops-admin'       => [
                'workshops',
            ],

            'shops-admin'               => [
                'shops',
            ],
            'shops-clerk'               => [
                'shops.view',
                'shops.edit',
            ],
            'shops-broadcaster'               => [
                'shops.view',
                'shops.broadcasting',
            ],
            'customer-services-admin' =>
                [
                    'shops.view',
                    'shops.customers',
                ],
            'customer-services-clerk' =>
                [
                    'shops.view',
                    'shops.customers.view',
                    'shops.customers.edit',
                ],

            'distribution-admin'        => [
                'inventory',
                'warehouses',
            ],
            'distribution-clerk'        => [
                'inventory.stocks',
                'warehouses.view',
                'warehouses.stock',
            ],
            'distribution-dispatcher-admin'   => [

                'inventory.stocks.view',
                'warehouses.view',
                'warehouses.dispatching',
            ],
            'distribution-dispatcher-picker'   => [

                'inventory.stocks.view',
                'warehouses.view',
                'warehouses.dispatching.pick',
            ],
            'distribution-dispatcher-packer'   => [

                'inventory.stocks.view',
                'warehouses.view',
                'warehouses.dispatching.pack',
            ],
            'accounts-admin'            => [
                'financials',
            ],
            'accounts-clerk'            => [
                'financials.view',
                'financials.edit',
            ],
            'accounts-receivable-admin' => [
                'financials.accounts_receivable',
            ],
            'accounts-receivable-clerk' => [
                'financials.accounts_receivable.view',
                'financials.accounts_receivable.edit',
            ],
            'accounts-payable-admin'    => [
                'financials.accounts_payable',
            ],
            'accounts-payable-clerk'    => [
                'financials.accounts_payable.view',
                'financials.accounts_payable.edit',
            ],
            'business-intelligence-analyst' =>[
                'financials.view',
                'shops.view',
                'websites.view',
                'inventory.stocks.view',
                'warehouses.view',
                'workshops.view',
            ]

        ],
        'model_roles' => [
            'Shop'      => [
                'shop-#-admin'             =>
                    [
                        'shops.#',
                    ],
                'shop-#-clerk'             =>
                    [
                        'shops.#.view',
                        'shops.#.products',
                    ],
                'customer-services-#-admin' =>
                    [
                        'shops.#.view',
                        'shops.#.customers',
                    ],
                'customer-services-#-clerk' =>
                    [
                        'shops.#.view',
                        'shops.#.customers.view',
                        'shops.#.customers.edit',
                    ]
            ],
            'Website'   => [
                'websites-#-admin' =>
                    [
                        'websites.#',
                    ],
                'websites-#-clerk' =>
                    [
                        'websites.#.edit',
                        'websites.#.view',
                        'websites.#.publish',
                    ],

            ],
            'Warehouse' => [
                'distribution-#-admin' =>
                    [
                        'warehouse.#',
                    ],
                'distribution-#-clerk' =>
                    [
                        'warehouse.#.view',
                        'warehouse.#.stock',
                    ],
                'distribution-dispatcher-#-admin' =>
                    [
                        'warehouse.#.view',
                        'warehouse.#.dispatching',
                    ],
                'distribution-dispatcher-#-picker' =>
                    [
                        'warehouse.#.view',
                        'warehouse.#.dispatching.pick',
                    ],
                'distribution-dispatcher-#-packer' =>
                    [
                        'warehouse.#.view',
                        'warehouse.#.dispatching.packer',
                    ]
            ],
            'Workshop' => [
                'workshop-#-admin' =>
                    [
                        'workshop.#',
                    ],
                'workshop-#-operative' =>
                    [
                        'workshop.#.view',
                        'workshop.#.stock',
                    ]
            ]
        ]
    ],
    'health' => [
        'name'        => 'Health',
        'modules'     => [
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
            'human_resources' => $human_resources,
            'account'         => $account


        ],
        'permissions' => [
            'users.create',
            'users.edit',
            'users.delete',
            'users',
            'look-and-field',
            'employees.edit',
            'employees.delete',
            'employees.payroll',
            'employees.confidential',
            'employees.attendance',
            'employees'
        ],
        'roles'       => [

            'super-admin'            => [],
            'account-admin'           => [
                'users',
                'look-and-field',
            ],
            'human-resources-clerk'  => [],
            'human-resources-admin*' => [],

        ]
    ]

];
