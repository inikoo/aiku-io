<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 28 Jan 2022 15:17:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\CRM\Customer;


use App\Actions\Marketing\Shop\ShowShop;
use App\Models\Marketing\Shop;
use Lorisleiva\Actions\ActionRequest;

use function __;


/**
 * @property Shop $shop
 */
class IndexCustomerInShop extends IndexCustomer
{


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("shops.customers.view") || $request->user()->hasPermissionTo("shops.customers.view.{$this->shop->id}");
    }

    public function queryConditions($query){
        return $query->where('shop_id',$this->shop->id)->select($this->select);
    }

    public function asInertia(Shop $shop)
    {
        $this->set('shop', $shop);
        $this->validateAttributes();
        unset($this->columns['shop_code']);
        return $this->getInertia();

    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'title' => __('Customers in :shop', ['shop' => $this->shop->code]),
                'breadcrumbs'=>$this->getBreadcrumbs($this->shop),
                'sectionRoot'=>'marketing.shops.show.customers.index',
                'metaSection' => 'shop'
            ]
        );
        $this->fillFromRequest($request);

    }

    public function getBreadcrumbs(Shop $shop): array
    {
        return array_merge(
            (new ShowShop())->getBreadcrumbs($shop),
            [
                'marketing.shops.show.customers.index' => [
                    'route'   => 'marketing.shops.show.customers.index',
                    'routeParameters' => $shop->id,

                    'modelLabel'=>[
                        'label'=>__('customers')
                    ],

                ],
            ]
        );
    }



}
