<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 14 Apr 2022 23:14:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Marketing;


trait UseCatalogue
{

    public function getCatalogueTabs($tab): array
    {
        return [
            [
                'name'    => __('Departments'),
                'href'    => [
                    'route'           => 'marketing.shops.show.catalogue.set',
                    'routeParameters' => [$this->shop->id],
                    'data'            => [
                        'catalogue' => 'departments'
                    ]
                ],
                'current' => $tab == 'departments'
            ],
            [
                'name'    => __('Families'),
                'href'    => [
                    'route'           => 'marketing.shops.show.catalogue.set',
                    'routeParameters' => [$this->shop->id],
                    'data'            => [
                        'catalogue' => 'families'
                    ],
                ],
                'current' => $tab == 'families'

            ],
            [
                'name'    => __('Products'),
                'href'    => [
                    'route'           => 'marketing.shops.show.catalogue.set',
                    'routeParameters' => [$this->shop->id],
                    'data'            => [
                        'catalogue' => 'products'
                    ],
                ],
                'current' => $tab == 'products'

            ]
        ];
    }

}

