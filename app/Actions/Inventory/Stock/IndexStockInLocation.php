<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 17 Mar 2022 02:26:29 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\Stock;


use App\Actions\Inventory\Location\ShowLocation;
use App\Models\Inventory\Location;
use Lorisleiva\Actions\ActionRequest;


use function __;


/**
 * @property Location $location
 * @property string $parent
 */
class IndexStockInLocation extends IndexStock
{


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("warehouses.view.{$this->location->warehouse_id}");
    }

    public function queryConditions($query)
    {
        return $query
            ->select($this->select)
            ->leftJoin('location_stock','location_stock.stock_id','stocks.id')
            ->where('location_stock.location_id',$this->location->id);
    }

    public function asInertia(string $parent, Location $location)
    {
        $this->parent   = $parent;
        $this->location = $location;
        $this->validateAttributes();

        return $this->getInertia();
    }

    public function prepareForValidation(ActionRequest $request): void
    {

         $this->perPage=500;

        $request->merge(
            [
                'title'              => __('Stocks in location'),
                'breadcrumbs'        => $this->getBreadcrumbs($this->parent, $this->location),
                'sectionRoot'        => 'inventory.stocks.index',
                'module'             => 'inventory',
                'metaSection'        => session('currentWarehouse') ? 'warehouse' : 'warehouses',
                'tabRoute'           => 'inventory.stocks.state',
                'tabRouteParameters' => [],

            ]
        );

        $this->fillFromRequest($request);

    }


    public function getBreadcrumbs(string $parent, Location $location): array
    {
        return array_merge(
            (new ShowLocation())->getBreadcrumbs($parent, $location),
            [
                '_location.stocks.index' => [
                    'route'      => 'inventory.stocks.index',
                    'modelLabel' => [
                        'label' => __('stocks')
                    ],
                ],
            ]
        );
    }


}

