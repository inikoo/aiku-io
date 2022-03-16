<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 16 Mar 2022 17:17:55 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\Stock;


use App\Actions\Inventory\ShowInventoryDashboard;
use App\Models\Inventory\Warehouse;
use Lorisleiva\Actions\ActionRequest;


use function __;


class IndexInProcessStockInTenant extends IndexStockInTenant
{


    public function queryConditions($query)
    {
        return $query
            ->where('state','in-process')
            ->select($this->select);
    }


    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'title'              => __('Stocks in process'),
                'breadcrumbs'        => $this->getBreadcrumbs(),
                'sectionRoot'        => 'inventory.stocks.index',
                'module'             => 'inventory',
                'metaSection'        => session('currentWarehouse') ? 'warehouse' : 'warehouses',
                'tabRoute'           => 'inventory.stocks.state',
                'tabRouteParameters' => []

            ]
        );

        $this->fillFromRequest($request);

        $this->set('tabs',$this->getTabs('in-process'));
    }


    public function getBreadcrumbs(): array
    {
        return array_merge(
            (new ShowInventoryDashboard())->getBreadcrumbs(),
            [
                'inventory.stocks.state' => [
                    'route'      => 'inventory.stocks.state',
                    'routeParameters'=>['in-process'],
                    'modelLabel' => [
                        'label' => __('stocks')
                    ],
                ],
            ]
        );
    }


}

