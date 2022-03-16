<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 16 Mar 2022 15:46:09 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\Stock;


use App\Actions\Inventory\ShowInventoryDashboard;
use App\Models\Inventory\Warehouse;
use Lorisleiva\Actions\ActionRequest;


use function __;

/**
 * @property Warehouse $warehouse
 */
class IndexStockInTenant extends IndexStock
{


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("inventory.stocks.view");
    }




    public function asInertia()
    {
        $this->validateAttributes();

        return $this->getInertia();

    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'title' => __('Stocks'),
                'breadcrumbs' => $this->getBreadcrumbs(),
                'sectionRoot' => 'inventory.stocks.index',
                'module' => 'inventory',
                'metaSection' => session('currentWarehouse') ? 'warehouse' : 'warehouses',

            ]
        );
        $this->fillFromRequest($request);
    }


    public function getBreadcrumbs(): array
    {
        return array_merge(
            (new ShowInventoryDashboard())->getBreadcrumbs(),
            [
                'inventory.stocks.index' => [
                    'route'      => 'inventory.stocks.index',
                    'modelLabel' => [
                        'label' => __('stocks')
                    ],
                ],
            ]
        );
    }


}

