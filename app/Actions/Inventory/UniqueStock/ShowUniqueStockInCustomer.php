<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 13 Feb 2022 03:34:08 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\UniqueStock;

use App\Actions\CRM\Customer\ShowCustomerInShop;
use App\Models\Inventory\UniqueStock;
use Lorisleiva\Actions\ActionRequest;


/**
 * @property UniqueStock $uniqueStock
 */
class ShowUniqueStockInCustomer extends ShowUniqueStock
{


    public function handle()
    {
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("shops.customers.view.{$this->uniqueStock->customer->shop_id}");
    }


    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'title'       => $this->uniqueStock->getFormattedId(),
                'breadcrumbs' => $this->getBreadcrumbs($this->uniqueStock),
                'sectionRoot' => 'marketing.shops.show.customers.index',
                'metaSection' => 'shop',
                'module'      => 'marketing'


            ]
        );
        $this->fillFromRequest($request);
    }


    public function getBreadcrumbs(UniqueStock $uniqueStock): array
    {
        return array_merge(
            (new ShowCustomerInShop())->getBreadcrumbs($uniqueStock->customer),
            [
                'marketing.shops.show.customers.show.unique_stocks.show' => [
                    'index'           => [
                        'route'           => 'marketing.shops.show.customers.show.unique_stocks.index',
                        'routeParameters' => [$uniqueStock->customer->shop_id, $uniqueStock->customer->id],
                        'overlay'         => __('Stored goods index')
                    ],
                    'modelLabel'      => [
                        'label' => __('stored good')
                    ],
                    'route'           => 'marketing.shops.show.customers.show.unique_stocks.show',
                    'routeParameters' => [$uniqueStock->customer->shop_id, $uniqueStock->customer->id, $uniqueStock->id],
                    'name'            => $uniqueStock->getFormattedID(),
                ],
            ]
        );
    }


}
