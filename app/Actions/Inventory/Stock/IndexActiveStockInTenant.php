<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 16 Mar 2022 17:08:26 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\Stock;


use App\Actions\Inventory\ShowInventoryDashboard;
use App\Models\Inventory\Warehouse;
use Lorisleiva\Actions\ActionRequest;


use function __;


class IndexActiveStockInTenant extends IndexStockInTenant
{


    public function queryConditions($query)
    {
        return $query
            ->where('state','active')
            ->select($this->select);
    }


    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'title'              => __('Active stocks'),
                'breadcrumbs'        => $this->getBreadcrumbs(),
                'sectionRoot'        => 'inventory.stocks.index',
                'module'             => 'inventory',
                'metaSection'        => session('currentWarehouse') ? 'warehouse' : 'warehouses',
                'tabRoute'           => 'inventory.stocks.state',
                'tabRouteParameters' => []

            ]
        );

        $this->fillFromRequest($request);

        $this->set('tabs',$this->getTabs('active'));
    }


    public function getBreadcrumbs(): array
    {
        return array_merge(
            (new ShowInventoryDashboard())->getBreadcrumbs(),
            [
                'inventory.stocks.state' => [
                    'route'      => 'inventory.stocks.state',
                    'routeParameters'=>['active'],
                    'modelLabel' => [
                        'label' => __('stocks')
                    ],
                ],
            ]
        );
    }


}

