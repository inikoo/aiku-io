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

            'shops' => [
                'id'           => 'shops',
                'bgColor'      => 'pink',
                'type'         => 'modelIndex',
                'with_options' => true,
                'route'        => 'shops.index',
                'permissions'  => ['shops.view'],
                'name'         => 'Stores',
                'code'         => 'Stores',
                'icon'         => ['fal', 'store-alt'],
                'sections'     => [
                    'shops.customers.index' => [
                        'name' => 'Customers',
                    ],
                    'shops.orders.index'    => [
                        'name' => 'Orders',
                    ],


                ]
            ],
            'shop'  => [
                'id'         => 'shop',
                'bgColor'    => 'pink',
                'modelIndex' => 'shops.index',
                'icon'       => ['fal', 'store-alt'],
                'type'       => 'modelOptions',
                'name'       => 'Store',
                'code'       => 'Store',

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

            'websites'   => [
                'id' => 'websites',


                'type'         => 'modelIndex',
                'with_options' => true,
                'route'        => 'websites.index',
                'permissions'  => ['websites.view'],
                'name'         => 'Websites',
                'code'         => 'Websites',
                'icon'         => ['fal', 'globe'],


            ],
            'website'    => [
                'id'         => 'website',
                'modelIndex' => 'websites.index',
                'icon'       => ['fal', 'globe'],
                'type'       => 'modelOptions',
                'name'       => 'Website',
                'code'       => 'Website',

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


            ],
            'warehouse'  => [
                'id'         => 'warehouse',
                'modelIndex' => 'warehouses.index',
                'icon'       => ['fal', 'warehouse-alt'],
                'type'       => 'modelOptions',
                'name'       => 'Warehouse',
                'code'       => 'WH',

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
                'id' => 'procurement',


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


            'warehouses.view',
            'warehouses.edit',
            'warehouses.delete',
            'warehouses.stock',
            'warehouses.lost_stock',
            'warehouses.dispatcher',
            'warehouses',

            'workshops.view',
            'workshops.edit',
            'workshops.dispatcher',
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
                'warehouse.#.dispatcher',
            ],
            'Workshop'  => [
                'workshops.#',
                'workshops.#.view',
                'workshops.#.edit',
                'workshops.#.delete',
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
                'warehouses',
                'financials',
            ],
            'tenant-admin'          => [
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
            'workshops-operative'   => [
                'workshops.view',
            ],
            'workshops-dispatcher'  => [
                'workshops.view',
                'workshops.dispatcher',
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
            'customer-services-*-admin' =>
                [
                    'shops.view',
                    'shops.customers',
                ],
            'customer-services-*-clerk' =>
                [
                    'shops.view',
                    'shops.customers.view',
                    'shops.customers.edit',
                ],

            'distribution-admin'        => [
                'warehouses',
            ],
            'distribution-clerk'        => [
                'warehouses.view',
                'warehouses.stock',
            ],
            'distribution-dispatcher'   => [
                'warehouses.view',
                'warehouses.dispatcher',
            ],
            'distribution-operative'    => [
                'warehouses.view',
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

        ],
        'model_roles' => [
            'Shop'      => [
                'shops-#-admin'             =>
                    [
                        'shops.#',
                    ],
                'shops-#-clerk'             =>
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
                'distribution-warehouse-#-admin' =>
                    [
                        'warehouse.#',
                    ],
                'distribution-warehouse-#-clerk' =>
                    [
                        'warehouse.#.view',
                        'warehouse.#.stock',
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
            'tenant-admin'           => [
                'users',
                'look-and-field',
            ],
            'human-resources-clerk'  => [],
            'human-resources-admin*' => [],

        ]
    ]

];
