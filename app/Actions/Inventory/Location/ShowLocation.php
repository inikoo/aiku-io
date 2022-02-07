<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 01 Feb 2022 00:27:34 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\Location;


use App\Actions\Inventory\Warehouse\IndexWarehouse;
use App\Actions\Inventory\Warehouse\ShowWarehouse;
use App\Actions\Inventory\WarehouseArea\ShowWarehouseArea;
use App\Actions\UI\WithInertia;
use App\Models\Inventory\Location;
use App\Models\Inventory\Warehouse;
use App\Models\Inventory\WarehouseArea;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


/**
 * @property Location $location
 * @property string $parent
 * @property bool $canEdit
 */
class ShowLocation
{
    use AsAction;
    use WithInertia;


    public function handle()
    {
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("warehouses.view.{$this->location->warehouse_id}");
    }


    public function asInertia(string $parent, ?Warehouse $warehouse, ?WarehouseArea $warehouseArea, Location $location, array $attributes = []): Response
    {
        $this->set('parent', $parent)
            ->set('location', $location)
            ->set('warehouse', $warehouse)
            ->set('warehouseArea', $warehouseArea)
            ->fill($attributes);

        $this->validateAttributes();


        session(['currentWarehouse' => $location->warehouse_id]);

        $actionIcons = [];

        if ($this->canEdit) {
            $actionIcons[$this->get('editRoute')] = [
                'routeParameters' => $this->get('editRouteParameters'),
                'name'            => __('Edit'),
                'icon'            => ['fal', 'edit']
            ];
        }


        return Inertia::render(
            'show-model',
            [
                'headerData' => [
                    'module'      => 'warehouses',
                    'title'       => __('Location').': '.$location->code,
                    'breadcrumbs' => $this->getBreadcrumbs($this->parent, $location),
                    'actionIcons' => $actionIcons,

                ],
                'model'      => $location
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);
        $this->set('canEdit', $request->user()->can("warehouses.edit.{$this->location->warehouse_id}"));

        switch ($this->parent) {
            case 'warehouseArea':
                $this->set('editRoute', 'warehouses.show.areas.show.locations.edit');
                $this->set('editRouteParameters', [$this->location->warehouse_id, $this->location->warehouse_area_id, $this->location->id]);

                break;
            case 'warehouse':
                $this->set('editRoute', 'warehouses.show.locations.edit');
                $this->set('editRouteParameters', [$this->location->warehouse_id, $this->location->id]);
                break;
            case 'tenant':
                $this->set('editRoute', 'warehouses.locations.edit');
                $this->set('editRouteParameters', []);

                break;
        }
    }


    public function getBreadcrumbs(string $parent, Location $location): array
    {
        $commonItems = [
            'name'  => $location->code,
            'model' => [
                'label' => __('Location'),
                'icon'  => ['fal', 'pallet-alt'],
            ],
        ];

        if ($parent == 'warehouseArea' and $location->warehouse_area_id) {
            return array_merge(
                (new ShowWarehouseArea())->getBreadcrumbs('warehouse',$location->warehouseArea),
                [
                    'warehouses.show.areas.show.locations.show' =>
                        array_merge(
                            [
                                'route'           => 'warehouses.show.areas.show.locations.show',
                                'routeParameters' => [
                                    $location->warehouse_id,
                                    $location->warehouse_area_id,
                                    $location->id
                                ],
                            ],
                            $commonItems
                        ),
                ]
            );
        } elseif ($parent == 'warehouse' and $location->warehouse_id) {
            return array_merge(
                (new ShowWarehouse())->getBreadcrumbs($location->warehouse),
                [
                    'warehouses.show.locations.show' =>
                        array_merge(
                            [
                                'route'           => 'warehouses.show.locations.show',
                                'routeParameters' => [
                                    $location->warehouse_id,
                                    $location->id
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
                                        'route' => 'warehouses.locations.index',
                                    ], $commonItems),
                ]
            );
        }


    }


}
