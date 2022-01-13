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

$system = [
    'id'          => 'system',
    'type'        => 'standard',
    'route'       => 'system.index',
    'permissions' => ['users.view'],
    'name'        => 'My account',
    'code'        => 'My account',

    'icon'        => ['fal', 'user-circle'],
    'sections'    => [
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

        'name'              => 'B2B commerce',
        'modules'           => [

            'dashboard' => [
                'type'     => 'home',
                'route'    => 'dashboard.index',
                'name'     => 'Dashboard',
                'code'     => 'Dashboard',
                'icon'     => ['fal', 'tachometer-alt-fast'],
                'sections' => []
            ],

            'shops' => [
                'id'           => 'shops',
                'type'         => 'modelIndex',
                'with_options' => true,
                'route'        => 'shops.index',
                'permissions'  => ['shops.view'],
                'name'         => 'Stores',
                'code'         => 'Stores',
                'icon'     => ['fal', 'store-alt'],
                'sections' => [
                    'shops.customers.index' => [
                        'name' => 'Customers',
                    ],
                    'shops.orders.index'    => [
                        'name' => 'Orders',
                    ],


                ]
            ],
            'shop'  => [
                'id'          => 'shop',
                'icon'        => ['fal', 'store-alt'],
                'type'        => 'modelOptions',
                'name'        => 'Store',
                'code'        => 'Store',

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
                'id'          => 'fulfilment_house',
                'icon'        => ['fal', 'person-carry'],
                'type'        => 'modelOptions',
                'name'        => 'Fulfilment',
                'code'        => 'FH',

            ],


            'human_resources' => $human_resources,
            'system'          => $system


        ],
        'permissions'       => [
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.*',
            'look-and-field',
            'employees.view',
            'employees.edit',
            'employees.delete',
            'employees.payroll',
            'employees.confidential',
            'employees.attendance',
            'employees.*',
            'agents.view',
            'agents.edit',
            'agents.delete',
            'agents.*',
            'suppliers.view',
            'suppliers.edit',
            'suppliers.delete',
            'suppliers.*',

            'shops.*',
            'shops.*.*',
            'shops.*.*.*',
            'shops.view',
            'shops.edit',
            'shops.delete',

            'shops.products.*',
            'shops.products.view',
            'shops.products.edit',
            'shops.products.delete',

            'shops.customers.*',
            'shops.customers.view',
            'shops.customers.edit',
            'shops.customers.delete',

            'shops.broadcasting.*',
            'shops.broadcasting.view',
            'shops.broadcasting.edit',
            'shops.broadcasting.send',
            'shops.broadcasting.delete',

            'shops.website.*',
            'shops.website.view',
            'shops.website.edit',
            'shops.website.publish',
            'shops.website.delete',


            'accounts.*',
            'accounts.view',
            'accounts.edit',
            'accounts.delete',

            'accounts.receivable.*',
            'accounts.receivable.view',
            'accounts.receivable.edit',
            'accounts.receivable.delete',

            'accounts.payable.*',
            'accounts.payable.view',
            'accounts.payable.edit',
            'accounts.payable.delete',


            'warehouses.view',
            'warehouses.edit',
            'warehouses.delete',
            'warehouses.stock',
            'warehouses.lost_stock',
            'warehouses.dispatcher',
            'warehouses.*',

            'production.view',
            'production.edit',
            'production.dispatcher',
            'production.delete',
            'production.*',


        ],
        'model_permissions' => [
            'Shops'      => [
                'shops.#.*',
                'shops.#.view',
                'shops.#.edit',
                'shops.#.delete',

                'shops.#.products.*',
                'shops.#.products.view',
                'shops.#.products.edit',
                'shops.#.products.delete',

                'shops.#.customers.*',
                'shops.#.customers.view',
                'shops.#.customers.edit',
                'shops.#.customers.delete',

                'shops.#.broadcasting.*',
                'shops.#.broadcasting.view',
                'shops.#.broadcasting.edit',
                'shops.#.broadcasting.send',
                'shops.#.broadcasting.delete',

                'shops.#.website.*',
                'shops.#.website.view',
                'shops.#.website.edit',
                'shops.#.website.publish',
                'shops.#.website.delete',


            ],
            'Warehouse' => [
                'warehouse.#.*',
                'warehouse.#.view',
                'warehouse.#.edit',
                'warehouse.#.delete',
                'warehouse.#.stock',
                'warehouse.#.lost_stock',
                'warehouse.#.dispatcher',
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
                'users.*',
                'look-and-field',
                'employees.*',
                'agents.*',
                'suppliers.*',
                'production.*',
                'shops.*',
                'warehouses.*',
                'accounts.*',
            ],
            'system-admin'          => [
                'users.*',
                'look-and-field',
            ],
            'human-resources-clerk' => [
                'employees.view',
                'employees.edit',
                'employees.payroll',
                'employees.attendance',
            ],
            'human-resources-admin' => [
                'employees.*',
            ],
            'buyer-clerk'           => [
                'agents.view',
                'agents.edit',
                'suppliers.view',
                'suppliers.edit',
            ],
            'buyer-admin'           => [
                'agents.*',
                'suppliers.*',
            ],
            'production-operative'  => [
                'production.view',
            ],
            'production-dispatcher' => [
                'production.view',
                'production.dispatcher',
            ],
            'production-admin'      => [
                'production.*',
            ],

            'shops-admin'               => [
                'shops.*',
            ],
            'shops-clerk'               => [
                'shops.view',
                'shops.edit',
            ],
            'customer-services-*-admin' =>
                [
                    'shops.view',
                    'shops.customers.*',
                ],
            'customer-services-*-clerk' =>
                [
                    'shops.view',
                    'shops.customers.view',
                    'shops.customers.edit',
                ],

            'distribution-admin'        => [
                'warehouses.*',
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
                'accounts.*',
            ],
            'accounts-clerk'            => [
                'accounts.view',
                'accounts.edit',
            ],
            'accounts-receivable-admin' => [
                'accounts.receivable.*',
            ],
            'accounts-receivable-clerk' => [
                'accounts.receivable.view',
                'accounts.receivable.edit',
            ],
            'accounts-payable-admin'    => [
                'accounts.payable.*',
            ],
            'accounts-payable-clerk'    => [
                'accounts.payable.view',
                'accounts.payable.edit',
            ],

        ],
        'model_roles' => [
            'Shops'      => [
                'shops-#-admin'             =>
                    [
                        'shops.#.*',
                    ],
                'shops-#-clerk'             =>
                    [
                        'shops.#.view',
                        'shops.#.products.*',
                    ],
                'customer-services-#-admin' =>
                    [
                        'shops.#.view',
                        'shops.#.customers.*',
                    ],
                'customer-services-#-clerk' =>
                    [
                        'shops.#.view',
                        'shops.#.customers.view',
                        'shops.#.customers.edit',
                    ]
            ],
            'Warehouse' => [
                'distribution-warehouse-#-admin' =>
                    [
                        'warehouse.#.*',
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
