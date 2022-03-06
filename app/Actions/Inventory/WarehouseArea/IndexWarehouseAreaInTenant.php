<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 07 Mar 2022 02:47:10 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\WarehouseArea;

use App\Actions\Inventory\ShowInventoryDashboard;
use Lorisleiva\Actions\ActionRequest;


use function __;


class IndexWarehouseAreaInTenant extends IndexWarehouseArea
{

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("warehouses.view");
    }

    public function queryConditions($query)
    {
        return $query->select($this->select)
            ->leftJoin('warehouses', 'warehouses.id', 'warehouse_id');
    }

    public function asInertia()
    {
        $this->validateAttributes();

        return $this->getInertia();
    }

    public function prepareForValidation(ActionRequest $request): void
    {



        $this->columns['code']['components'][0]['resolver']['parameters']['href'] = [
            'route'   => 'inventory.areas.show',
            'indices' => ['id'],
        ];
        $this->columns['number_locations']['components'][0]['resolver']['parameters']['href'] = [
            'route'   => 'inventory.areas.show.locations.index',
            'indices' => ['id'],
        ];

        $request->merge(
            [
                'title'       => __('Areas'),
                'module'      => 'inventory',
                'metaSection' => 'warehouses',
                'sectionRoot' => 'inventory.warehouses.index',
                'breadcrumbs' => $this->getBreadcrumbs()


            ]
        );
        $this->fillFromRequest($request);
    }


    public function getBreadcrumbs(): array
    {
        return array_merge(
            (new ShowInventoryDashboard())->getBreadcrumbs(),
            [
                'inventory.areas.index' => [
                    'route'      => 'inventory.areas.index',
                    'modelLabel' => [
                        'label' => __('areas')
                    ],
                ]
            ]
        );
    }


}
