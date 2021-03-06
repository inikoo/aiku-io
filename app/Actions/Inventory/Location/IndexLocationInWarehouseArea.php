<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 31 Jan 2022 04:16:17 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\Location;


use App\Actions\Inventory\WarehouseArea\ShowWarehouseArea;
use App\Models\Inventory\WarehouseArea;
use Lorisleiva\Actions\ActionRequest;


use function __;

/**
 * @property WarehouseArea $warehouseArea
 */
class IndexLocationInWarehouseArea extends IndexLocation
{

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("warehouses.view");
    }

    public function queryConditions($query)
    {
        return $query->where('locations.warehouse_area_id', $this->warehouseArea->id)
            ->select($this->select);
    }

    public function asInertia(WarehouseArea $warehouseArea)
    {
        $this->set('warehouseArea', $warehouseArea);
        $this->validateAttributes();

        return $this->getInertia();
    }


    public function prepareForValidation(ActionRequest $request): void
    {
        unset($this->columns['warehouse_code']);
        unset($this->columns['warehouse_area_code']);


        $this->columns['code']['components'][0]['resolver']['parameters']['href'] = [
            'route'   => 'inventory.areas.show.locations.show',
            'indices' => ['warehouse_area_id', 'id']
        ];

        $request->merge(
            [

                'title'       => __('Locations in :warehouse', ['warehouse' => $this->warehouseArea->code]),
                'breadcrumbs' => $this->getBreadcrumbs($this->warehouseArea),
                'sectionRoot' => 'inventory.warehouses.index',
                'metaSection' => 'warehouses'

            ]
        );
        $this->fillFromRequest($request);
    }


    public function getBreadcrumbs(WarehouseArea $warehouseArea): array
    {
        return array_merge(
            (new ShowWarehouseArea())->getBreadcrumbs('tenant', $warehouseArea),
            [
                'inventory.areas.show.locations.index' => [
                    'route'           => 'inventory.areas.show.locations.index',
                    'routeParameters' => [$warehouseArea->id],
                    'modelLabel'      => [
                        'label' => __('locations')
                    ],
                ]
            ]
        );
    }


}
