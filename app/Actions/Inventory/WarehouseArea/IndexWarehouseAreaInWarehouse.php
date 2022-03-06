<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 31 Jan 2022 03:39:55 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\WarehouseArea;

use App\Actions\Inventory\Warehouse\ShowWarehouse;
use App\Models\Inventory\Warehouse;
use Lorisleiva\Actions\ActionRequest;


use function __;

/**
 * @property Warehouse $warehouse
 */
class IndexWarehouseAreaInWarehouse extends IndexWarehouseArea
{

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("warehouses.view.{$this->warehouse->id}");
    }

    public function queryConditions($query)
    {
        return $query->where('warehouse_id', $this->warehouse->id)->select($this->select);
    }

    public function asInertia(Warehouse $warehouse)
    {
        $this->set('warehouse', $warehouse);
        $this->validateAttributes();

        return $this->getInertia();
    }

    public function prepareForValidation(ActionRequest $request): void
    {


        $request->merge(
            [
                'title'       => __('Areas in :warehouse', ['warehouse' => $this->warehouse->code]),
                'module'      => 'inventory',
                'metaSection' => 'warehouse',
                'sectionRoot' => 'inventory.warehouses.show.areas.index',
                'breadcrumbs' => $this->getBreadcrumbs($this->warehouse)


            ]
        );
        $this->fillFromRequest($request);

    }


    public function getBreadcrumbs(Warehouse $warehouse): array
    {
        return array_merge(
            (new ShowWarehouse())->getBreadcrumbs($warehouse),
            [
                'inventory.warehouses.show.areas.index' => [
                    'route'           => 'inventory.warehouses.show.areas.index',
                    'routeParameters' => $this->warehouse->id,
                    'modelLabel'=>[
                        'label'=>__('areas')
                    ],
                ]
            ]
        );
    }


}
