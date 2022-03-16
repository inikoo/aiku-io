<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 16 Mar 2022 17:39:38 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\Stock;


use App\Actions\Inventory\ShowInventoryDashboard;
use Lorisleiva\Actions\ActionRequest;


use function __;


class IndexDiscontinuedStockInTenant extends IndexStockInTenant
{


    public function queryConditions($query)
    {
        return $query
            ->where('state', 'discontinued')
            ->select($this->select);
    }


    public function prepareForValidation(ActionRequest $request): void
    {
        $this->select = [
            'id',
            'code',
            'description',
            'discontinued_at'
        ];

        $this->allowedSorts = ['code','discontinued_at'];

        $this->columns=array_merge($this->columns,
        [
            'discontinued_at' => [
                'sort'       => 'discontinued_at',
                'label'    => __('Discontinued at'),
                'resolver' => 'discontinued_at',
                'type'=>'date'
            ],
        ]
        );

        $request->merge(
            [
                'title'              => __('Discontinued stocks'),
                'breadcrumbs'        => $this->getBreadcrumbs(),
                'sectionRoot'        => 'inventory.stocks.index',
                'module'             => 'inventory',
                'metaSection'        => session('currentWarehouse') ? 'warehouse' : 'warehouses',
                'tabRoute'           => 'inventory.stocks.state',
                'tabRouteParameters' => []

            ]
        );

        $this->fillFromRequest($request);

        $this->set('tabs', $this->getTabs('discontinued'));
    }


    public function getBreadcrumbs(): array
    {
        return array_merge(
            (new ShowInventoryDashboard())->getBreadcrumbs(),
            [
                'inventory.stocks.state' => [
                    'route'           => 'inventory.stocks.state',
                    'routeParameters' => ['discontinued'],
                    'modelLabel'      => [
                        'label' => __('stocks')
                    ],
                ],
            ]
        );
    }


}

