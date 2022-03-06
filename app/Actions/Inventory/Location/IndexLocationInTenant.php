<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 07 Mar 2022 01:48:12 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\Location;


use App\Actions\Inventory\ShowInventoryDashboard;
use Lorisleiva\Actions\ActionRequest;


use function __;



class IndexLocationInTenant extends IndexLocation
{

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("warehouses.view");
    }

    public function queryConditions($query){
        return $query
            ->leftJoin('warehouses','warehouse_id','warehouses.id')
            ->leftJoin('warehouse_areas','warehouse_area_id','warehouse_areas.id')
            ->select($this->select);
    }

    public function asInertia()
    {

        $this->validateAttributes();

        return $this->getInertia();

    }



    public function prepareForValidation(ActionRequest $request): void
    {



       $this->select=array_merge(
           $this->select,[
                            'warehouse_areas.code as warehouse_area_code',
                            'warehouses.code as warehouse_code',
                        ]
       );

       if(session('inventoryCount')==1){
           unset($this->columns['warehouse_code']);
       }


        $request->merge(
            [
                'title' => __('Locations'),
                'breadcrumbs'=>$this->getBreadcrumbs(),
                'sectionRoot'=>'inventory.warehouses.index',
                'metaSection' => 'warehouses'
            ]
        );
        $this->fillFromRequest($request);

    }


    public function getBreadcrumbs(): array
    {
        return array_merge(
            (new ShowInventoryDashboard())->getBreadcrumbs(),
            [
                'inventory.locations.index' => [
                    'route'           => 'inventory.locations.index',
                    'modelLabel' => [
                        'label' => __('locations')
                    ],
                ]
            ]
        );
    }




}
