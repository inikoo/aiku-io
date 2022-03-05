<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 03 Feb 2022 18:34:27 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

return [

    'super-admin'           => [
        'assets',
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


    'assets-manager'          => [
        'assets',
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
    'workshop-operative'    => [
        'workshops.view',
    ],
    'workshops-admin'       => [
        'workshops',
    ],

    'shops-admin'             => [
        'shops',
    ],
    'shops-clerk'             => [
        'shops.view',
        'shops.edit',
    ],
    'shops-broadcaster'       => [
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

    'distribution-admin'             => [
        'inventory',
        'warehouses',
    ],
    'distribution-clerk'             => [
        'inventory.stocks',
        'warehouses.view',
        'warehouses.stock',
    ],
    'distribution-dispatcher-admin'  => [

        'inventory.stocks.view',
        'warehouses.view',
        'warehouses.dispatching',
    ],
    'distribution-dispatcher-picker' => [

        'inventory.stocks.view',
        'warehouses.view',
        'warehouses.dispatching.pick',
    ],
    'distribution-dispatcher-packer' => [

        'inventory.stocks.view',
        'warehouses.view',
        'warehouses.dispatching.pack',
    ],
    'accounts-admin'                 => [
        'financials',
    ],
    'accounts-clerk'                 => [
        'financials.view',
        'financials.edit',
    ],
    'accounts-receivable-admin'      => [
        'financials.accounts_receivable',
    ],
    'accounts-receivable-clerk'      => [
        'financials.accounts_receivable.view',
        'financials.accounts_receivable.edit',
    ],
    'accounts-payable-admin'         => [
        'financials.accounts_payable',
    ],
    'accounts-payable-clerk'         => [
        'financials.accounts_payable.view',
        'financials.accounts_payable.edit',
    ],
    'business-intelligence-analyst'  => [
        'financials.view',
        'shops.view',
        'websites.view',
        'inventory.stocks.view',
        'warehouses.view',
        'workshops.view',
    ]

];
