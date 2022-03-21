<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 04 Mar 2022 04:17:55 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\UniqueStock;


use App\Actions\CRM\Customer\ShowCustomerInShop;

use App\Models\CRM\Customer;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


use App\Actions\UI\WithInertia;


use function __;

/**
 * @property \Illuminate\Support\Collection $allowed_shops
 * @property bool $canViewAll
 * @property Customer $customer
 */
class IndexUniqueStockInCustomer extends IndexUniqueStock
{
    use AsAction;
    use WithInertia;


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("shops.view.{$this->customer->shop->id}");
    }


    public function queryConditions($query)
    {
        return $query->where('customer_id', $this->customer->id)->select($this->select);
    }

    public function asInertia(Customer $customer)
    {
        $this->set('customer', $customer);
        $this->validateAttributes();


        return $this->getInertia();
    }


    /**
     * @throws \Exception
     */
    public function prepareForValidation(ActionRequest $request): void
    {
        if ($this->customer->shop->subtype != 'fulfilment') {
            $this->getValidationFailure();
        }

        unset($this->columns['customer']);


        $this->columns['formatted_id']['components'][0]['resolver']['parameters']['href'] = [
            'route'   => 'marketing.shops.show.customers.show.unique_stocks.show',
            'indices' => [
                'shop_id',
                'customer_id',
                'id'
            ]
        ];


        $request->merge(
            [
                'title'       => __('Stored goods'),
                'subtitle'    => $this->customer->name,
                'breadcrumbs' => $this->getBreadcrumbs($this->customer),
                'module'      => 'marketing',
                'sectionRoot' => 'marketing.shops.show.customers.index',
                'metaSection' => 'shop'

            ]
        );
        $this->fillFromRequest($request);
    }


    public function getBreadcrumbs(Customer $customer): array
    {
        return array_merge(
            (new ShowCustomerInShop())->getBreadcrumbs($customer),
            [
                'marketing.shops.show.customers.show.unique_stocks.index' => [
                    'route'           => 'marketing.shops.show.customers.show.unique_stocks.index',
                    'routeParameters' => [$customer->shop_id, $customer->id],
                    'modelLabel'      => [
                        'label' => __('stored goods')
                    ],
                ],
            ]
        );
    }


}
