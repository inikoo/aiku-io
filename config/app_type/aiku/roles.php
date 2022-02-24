<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 22 Feb 2022 16:40:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */


return
[
    'super-admin'           => [
        'admin_users',
        'look-and-field',
        'tenants',
    ],
    'system-admin'          => [
        'admin_users',
        'look-and-field',
    ],
    'customer-services' =>[
        'tenants',
        'billing.view',
        'broadcasting.view',
    ],
    'customer-services-admin' =>[
        'tenants',
        'billing.view',
        'billing.edit',
        'broadcasting.view',
        'broadcasting.edit',
        'broadcasting.send',
    ],
    'marketing' =>[
        'tenants.view',
        'broadcasting',
    ],
    'financials' =>[
        'tenants.view',
        'billing'
    ]
];

