<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 03 Feb 2022 18:34:27 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

return [

    'super-admin' => [
        'assets',
        'account',
        'employees',
        'shops',
        'websites',
        'financials',
        'staffing',
    ],


    'assets-manager' => [
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
    'recruiter-admin' =>
        [
            'staffing.view',
            'staffing.applicants',
        ],
    'recruiter-clerk' =>
        [
            'staffing.view',
            'staffing.applicants.view',
            'staffing.applicants.edit',
        ],

    'accounts-admin'                => [
        'financials',
    ],
    'accounts-clerk'                => [
        'financials.view',
        'financials.edit',
    ],
    'accounts-receivable-admin'     => [
        'financials.accounts_receivable',
    ],
    'accounts-receivable-clerk'     => [
        'financials.accounts_receivable.view',
        'financials.accounts_receivable.edit',
    ],
    'accounts-payable-admin'        => [
        'financials.accounts_payable',
    ],
    'accounts-payable-clerk'        => [
        'financials.accounts_payable.view',
        'financials.accounts_payable.edit',
    ],
    'business-intelligence-analyst' => [
        'financials.view',
        'shops.view',
        'websites.view',
        'inventory.stocks.view',
        'warehouses.view',
        'workshops.view',
    ]

];
