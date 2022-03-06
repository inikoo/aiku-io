<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 29 Jan 2022 01:55:22 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\Location;


use App\Actions\Inventory\Warehouse\ShowWarehouse;
use App\Models\Inventory\Warehouse;
use Lorisleiva\Actions\ActionRequest;


use function __;


/**
 * @property Warehouse $warehouse
 */
class IndexLocationInWarehouse extends IndexLocation
{

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("warehouses.view.$this->warehouse->id");
    }

    public function queryConditions($query){
        return $query->where('locations.warehouse_id',$this->warehouse->id)
            ->leftJoin('warehouse_areas','warehouse_area_id','warehouse_areas.id')
            ->select($this->select);
    }

    public function asInertia(Warehouse $warehouse)
    {
        $this->set('warehouse', $warehouse);
        session(['currentWarehouse' => $warehouse->id]);
        $this->validateAttributes();

        return $this->getInertia();

    }



    public function prepareForValidation(ActionRequest $request): void
    {

        unset($this->columns['warehouse_code']);


        $this->select=array_merge(
            $this->select,[
                             'warehouse_areas.code as warehouse_area_code',
                         ]
        );

        $this->columns['code']['components'][0]['resolver']['parameters']['href']=[
            'route'  => 'inventory.warehouses.show.locations.show',
            'indices' => ['warehouse_id', 'id']
        ];
        $this->columns['warehouse_area_code']['components'][0]['resolver']['parameters']['href']=[
            'route'  => 'inventory.warehouses.show.areas.show',
            'indices' => ['warehouse_id', 'warehouse_area_id']
        ];

        $request->merge(
            [
                'title' => __('Locations in :warehouse', ['warehouse' => $this->warehouse->code]),
                'breadcrumbs'=>$this->getBreadcrumbs($this->warehouse),
                'sectionRoot'=>'inventory.warehouses.show.locations.index',
                'metaSection' => 'warehouse'

            ]
        );
        $this->fillFromRequest($request);

    }


    public function getBreadcrumbs(Warehouse $warehouse): array
    {
        return array_merge(
            (new ShowWarehouse())->getBreadcrumbs($warehouse),
            [
                'inventory.warehouses.show.locations.index' => [
                    'route'           => 'inventory.warehouses.show.locations.index',
                    'routeParameters' => $warehouse->id,
                    'modelLabel'      => [
                        'label' => __('location')
                    ],

                ]
            ]
        );
    }




}
