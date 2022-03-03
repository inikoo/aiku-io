<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 01 Mar 2022 03:55:15 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\CRM\Customer;


use App\Http\Resources\CRM\FulfilmentCustomerInertiaResource;
use App\Models\Marketing\Shop;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


/**
 * @property Shop $shop
 */
class IndexCustomerInFulfilmentShop extends IndexCustomerInShop
{

    public function __construct()
    {
        parent::__construct();
        $this->select = ['customers.id as id', 'name', 'shop_id', 'number_unique_stocks'];

        $this->columns += [

            'number_unique_stocks' => [
                'sort'       => 'number_unique_stocks',
                'label'      => __('Stored goods'),
                'components' => [
                    [
                        'type'     => 'link',
                        'resolver' => [
                            'type'       => 'link',
                            'parameters' => [
                                'href'    => [
                                    'route'   => 'marketing.shops.show.customers.show.unique_stocks.index',
                                    'indices' => ['shop_id', 'id']
                                ],
                                'indices' => 'number_unique_stocks'
                            ],
                        ]
                    ]
                ],
            ],

        ];

        $this->allowedSorts[] = 'number_unique_stocks';
    }

    public function queryConditions($query)
    {
        return $query->leftJoin('fulfilment_customers', 'fulfilment_customers.customer_id', 'customers.id')
            ->where('shop_id', $this->shop->id)->select($this->select);
    }

    protected function getRecords(): AnonymousResourceCollection
    {
        return FulfilmentCustomerInertiaResource::collection($this->handle());
    }


}
