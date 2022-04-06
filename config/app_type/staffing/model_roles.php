<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 03 Feb 2022 18:34:27 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

return [
    'Shop'      => [
        'shop-#-admin'              =>
            [
                'shops.#',
            ],
        'shop-#-clerk'              =>
            [
                'shops.view.#',
                'shops.products.#',
            ],
        'customer-services-#-admin' =>
            [
                'shops.view.#',
                'shops.customers.#',
            ],
        'customer-services-#-clerk' =>
            [
                'shops.view.#',
                'shops.customers.view.#',
                'shops.customers.edit.#',
            ]
    ],
    'Website'   => [
        'websites-#-admin' =>
            [
                'websites.#',
            ],
        'websites-#-clerk' =>
            [
                'websites.edit.#',
                'websites.view.#',
                'websites.publish.#',
            ],

    ],

];
