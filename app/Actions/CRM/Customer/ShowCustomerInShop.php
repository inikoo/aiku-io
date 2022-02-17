<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 13 Feb 2022 03:34:08 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\CRM\Customer;

use App\Actions\Marketing\Shop\IndexShop;
use App\Actions\Marketing\Shop\ShowShop;
use App\Models\CRM\Customer;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;


/**
 * @property Customer $customer
 */
class ShowCustomerInShop extends ShowCustomer
{



    public function handle()
    {
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("shops.customers.view.{$this->customer->shop_id}");
    }


    public function asInertia(Customer $customer, array $attributes = []): Response
    {
        $this->set('customer', $customer)->fill($attributes);

        $this->validateAttributes();

        return $this->getInertia();

    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'title' => $this->customer->name,
                'breadcrumbs'=>$this->getBreadcrumbs($this->customer),
                'sectionRoot'=>'marketing.shops.show.customers.index',
                'metaSection' => 'shop'


            ]
        );
        $this->fillFromRequest($request);
    }


    public function getBreadcrumbs(Customer $customer): array
    {
        return array_merge(
            (new showShop())->getBreadcrumbs($customer->shop),
            [
                'marketing.shops.show.customers.show' => [
                    'index'=>[
                        'route'   => 'marketing.shops.show.customers.index',
                        'routeParameters'=>[$customer->shop_id],
                        'overlay' => __('Customer index')
                    ],
                    'modelLabel'=>[
                        'label'=>__('customer')
                    ],
                    'route'           => 'marketing.shops.show.customers.show',
                    'routeParameters' => [$customer->shop_id, $customer->id],
                    'name'            => $customer->getFormattedID(),
                ],
            ]
        );
    }


}
