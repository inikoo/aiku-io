<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 04 Mar 2022 22:12:26 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\UniqueStock;

use App\Actions\Inventory\ShowInventoryDashboard;
use App\Models\Inventory\UniqueStock;
use Lorisleiva\Actions\ActionRequest;


/**
 * @property UniqueStock $uniqueStock
 */
class ShowUniqueStockInTenant extends ShowUniqueStock
{


    public function handle()
    {
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("inventory.stocks.view");
    }


    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'title'       => $this->uniqueStock->getFormattedId(),
                'breadcrumbs' => $this->getBreadcrumbs($this->uniqueStock),
                'sectionRoot' => 'inventory.unique_stocks.index',
                'module'      => 'inventory'
            ]
        );
        $this->fillFromRequest($request);
    }


    public function getBreadcrumbs(UniqueStock $uniqueStock): array
    {
        return array_merge(
            (new ShowInventoryDashboard())->getBreadcrumbs(),
            [
                'marketing.shops.show.customers.show.unique_stocks.show' => [
                    'index'           => [
                        'route'           => 'inventory.unique_stocks.index',
                        'overlay'         => __('Stored goods index')
                    ],
                    'modelLabel'      => [
                        'label' => __('stored good')
                    ],
                    'route'           => 'inventory.unique_stocks.show',
                    'routeParameters' => [$uniqueStock->id],
                    'name'            => $uniqueStock->getFormattedID(),
                ],
            ]
        );
    }


}
