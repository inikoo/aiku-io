<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 31 Jan 2022 04:21:07 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\WarehouseArea;


use App\Actions\Inventory\Warehouse\IndexWarehouse;
use App\Actions\Inventory\Warehouse\ShowWarehouse;
use App\Actions\UI\WithInertia;
use App\Models\Inventory\WarehouseArea;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


/**
 * @property WarehouseArea $warehouseArea
 * @property string $parent
 */
class ShowWarehouseArea
{
    use AsAction;
    use WithInertia;


    public function handle()
    {
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("warehouses.view.{$this->warehouseArea->warehouse_id}");
    }


    public function asInertia(string $parent, WarehouseArea $warehouseArea, array $attributes = []): Response
    {
        $this->set('parent', $parent)->set('warehouseArea', $warehouseArea)->fill($attributes);

        $this->validateAttributes();


        session(['currentWarehouse' => $warehouseArea->warehouse_id]);

        $actionIcons = [];
        if ($this->get('canEdit')) {
            $actionIcons[$this->get('editRoute')] = [
                'routeParameters' => $this->get('editRouteParameters'),
                'name'            => __('Edit'),
                'icon'            => ['fal', 'edit']
            ];
        }


        return Inertia::render(
            'Common/ShowModel',
            [
                'headerData' => [
                    'module'      => 'warehouses',
                    'title'       => __('Warehouse area').': '.$warehouseArea->name,
                    'breadcrumbs' => $this->getBreadcrumbs($this->parent, $warehouseArea),
                    'actionIcons' => $actionIcons,
                ],
                'model'      => $warehouseArea
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);
        $this->set('canEdit', $request->user()->can("warehouses.edit.{$this->warehouseArea->warehouse_id}"));

        switch ($this->parent) {
            case 'warehouse':
                $this->set('editRoute', 'warehouses.show.areas.edit');
                $this->set('editRouteParameters', [$this->warehouseArea->warehouse_id, $this->warehouseArea->id]);

                break;
            case 'tenant':
                $this->set('editRoute', 'warehouses.areas.edit');
                $this->set('editRouteParameters', []);

                break;
        }
    }


    public function getBreadcrumbs(string $parent, WarehouseArea $warehouseArea): array
    {
        $commonItems = [
            'name'  => $warehouseArea->code,
            'model' => [
                'label' => __('Warehouse area'),
                'icon'  => ['fal', 'inventory'],
            ],
        ];

        if ($parent == 'warehouse') {
            return array_merge(
                (new ShowWarehouse())->getBreadcrumbs($warehouseArea->warehouse),
                [
                    'warehouses.show.areas.show' =>
                        array_merge(
                            [
                                'route'           => 'warehouses.show.areas.show',
                                'routeParameters' => [
                                    $warehouseArea->warehouse_id,
                                    $warehouseArea->id
                                ],
                            ],
                            $commonItems
                        ),
                ]
            );
        } else {
            return array_merge(
                (new IndexWarehouse())->getBreadcrumbs(),
                [
                    'warehouses.areas.index' =>
                        array_merge([
                                        'route' => 'warehouses.areas.index',
                                    ], $commonItems),
                ]
            );
        }
    }


}
