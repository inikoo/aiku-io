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

        'name'              => 'B2B commerce',
        'modules'           => [
            'dashboard'       => [
                'route'    => 'dashboard.index',
                'name'     => 'Dashboard',
                'fa'       => ['fal', 'tachometer-alt-fast'],
                'sections' => []
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
            'employees.*'
            ,
            'agents.view',
            'agents.edit',
            'agents.delete',
            'agents.*',
            'suppliers.view',
            'suppliers.edit',
            'suppliers.delete',
            'suppliers.*',

            'shops.*',
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
            'Shop'      => [
                'shop.#.*',
                'shop.#.view',
                'shop.#.edit',
                'shop.#.delete',

                'shop.#.products.*',
                'shop.#.products.view',
                'shop.#.products.edit',
                'shop.#.products.delete',

                'shop.#.customers.*',
                'shop.#.customers.view',
                'shop.#.customers.edit',
                'shop.#.customers.delete',

                'shop.#.broadcasting.*',
                'shop.#.broadcasting.view',
                'shop.#.broadcasting.edit',
                'shop.#.broadcasting.send',
                'shop.#.broadcasting.delete',

                'shop.#.website.*',
                'shop.#.website.view',
                'shop.#.website.edit',
                'shop.#.website.publish',
                'shop.#.website.delete',


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


        'roles'       => [

            'super-admin'           => [],
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

            'shops-admin' => [
                'shops.*',
            ],
            'shops-clerk' => [
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
            'Shop'      => [
                'shop-#-admin'              =>
                    [
                        'shop.#.*',
                    ],
                'shop-#-clerk'              =>
                    [
                        'shop.#.view',
                        'shop.#.products.*',
                    ],
                'customer-services-#-admin' =>
                    [
                        'shop.#.view',
                        'shop.#.customers.*',
                    ],
                'customer-services-#-clerk' =>
                    [
                        'shop.#.view',
                        'shop.#.customers.view',
                        'shop.#.customers.edit',
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
