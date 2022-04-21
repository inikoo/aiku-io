<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 03 Feb 2022 18:34:27 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

return [


    'positions' => [

        'dir'   => [
            'slug'  => 'dir',
            'name'  => 'director',
            'roles' => [

            ]
        ],
        'hr-m'  => [
            'slug'  => 'hr-m',
            'grade' => 'manager',
            'name'  => 'Human resources supervisor',
            'roles' => [
                'human-resources-admin'
            ]
        ],
        'hr-c'  => [
            'slug'  => 'hr-c',
            'name'  => 'Human resources clerk',
            'grade' => 'clerk',
            'roles' => [
                'human-resources-clerk'
            ]
        ],
        'acc'   => [
            'slug'  => 'acc',
            'name'  => 'Accounts',
            'roles' => [

            ]
        ],
        'mrk-m' => [
            'slug'  => 'mrk-m',
            'grade' => 'manager',
            'name'  => 'Marketing supervisor',
            'roles' => [

            ]
        ],
        'mrk-c' => [
            'slug'  => 'mrk-c',
            'grade' => 'clerk',
            'name'  => 'Marketing clerk',
            'roles' => [

            ]
        ],

        'buy'      => [
            'slug'  => 'buy',
            'name'  => 'Buyer',
            'roles' => [

            ]
        ],
        'wah-m'    => [
            'slug'       => 'wah-m',
            'team'       => 'warehouse',
            'department' => 'procurement',
            'name'       => 'Warehouse supervisor',
            'roles'      => [

            ]
        ],
        'wah-sk'   => [
            'slug'       => 'wah-sk',
            'team'       => 'warehouse',
            'department' => 'warehouse',

            'name'  => 'Warehouse stock keeper',
            'roles' => [

            ]
        ],
        'wah-sc'   => [
            'slug'       => 'wah-sc',
            'name'       => 'Stock Controller',
            'team'       => 'warehouse',
            'department' => 'warehouse',
            'roles'      => [

            ]
        ],
        'dist-m'    => [
            'slug'       => 'dist-m',
            'name'       => 'Dispatch supervisor',
            'team'       => 'warehouse',
            'department' => 'warehouse',
            'roles'      => [

            ]
        ],
        'dist-pik' => [
            'slug'       => 'dist-pik',
            'team'       => 'warehouse',
            'department' => 'warehouse',
            'name'       => 'Picker',
            'roles'      => [

            ]
        ],
        'dist-pak' => [
            'slug'       => 'dist-pak',
            'team'       => 'warehouse',
            'department' => 'warehouse',
            'name'       => 'Packer',
            'roles'      => [

            ]
        ],
        'prod-m'   => [
            'slug'       => 'prod-m',
            'team'       => 'production',
            'department' => 'production',
            'name'       => 'Production supervisor',
            'roles'      => [

            ]
        ],
        'prod-w'   => [
            'slug'       => 'prod-w',
            'team'       => 'production',
            'department' => 'production',
            'name'       => 'Production operative',
            'roles'      => [

            ]
        ],
        'cus-m'    => [
            'slug'  => 'cus-m',
            'grade' => 'manager',
            'name'  => 'Customer service supervisor',
            'roles' => [

            ]
        ],
        'cus-c'    => [
            'slug'  => 'cus-c',
            'grade' => 'clerk',
            'name'  => 'Customer service',
            'roles' => [

            ]
        ],
    ],
    'wrappers'=>[
        'hr'=>['hr-m', 'hr-c'],
        'mrk'=>['mrk-m', 'mrk-c'],
        'cus'=>['cus-m', 'cus-c']
    ],

    'blueprint' => [
        'management' => [
            'title'     => 'management and operations',
            'positions' => [
                'dir' => 'dir',
                'acc' => 'acc',
                'buy' => 'buy',
                'hr'  => ['hr-m', 'hr-c'],


            ]
        ],
        'marketing'  => [
            'title'     => 'marketing and customer services',
            'positions' => [
                'mrk' => ['mrk-m', 'mrk-c'],
                'cus' => ['cus-m', 'cus-c'],


            ],
            'scope'=>'shops'
        ],
        'inventory'  => [
            'title'     => 'warehousing',
            'positions' => [



            ],
            'scope'=>'warehouses'
        ],

    ]
];
