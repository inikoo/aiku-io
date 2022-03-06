<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 01 Feb 2022 00:27:34 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\Location;


use App\Actions\Inventory\ShowInventoryDashboard;
use App\Actions\Inventory\Warehouse\ShowWarehouse;
use App\Actions\Inventory\WarehouseArea\ShowWarehouseArea;
use App\Actions\UI\WithInertia;
use App\Models\Inventory\Location;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


/**
 * @property Location $location
 * @property string $parent
 * @property bool $canEdit
 * @property string $module
 * @property string $metaSection
 * @property string $sectionRoot
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


    public function asInertia(string $parent, Location $location): Response
    {
        $this->set('parent', $parent)
            ->set('location', $location);

        $this->validateAttributes();


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
                'breadcrumbs' => $this->getBreadcrumbs($this->parent, $this->location),
                'navData'     => ['module' => $this->module, 'metaSection' => $this->metaSection, 'sectionRoot' => $this->sectionRoot],
                'headerData'  => [
                    'title'       => __('Location').': '.$location->code,
                    'actionIcons' => $actionIcons,

                ],
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);
        $this->set('canEdit', $request->user()->can("warehouses.edit.{$this->location->warehouse_id}"));

        $this->module = 'inventory';


        switch ($this->parent) {
            case 'warehouseAreaInWarehouse':
                $this->set('editRoute', 'inventory.warehouses.show.areas.show.locations.edit');
                $this->set('editRouteParameters', [$this->location->warehouse_id, $this->location->warehouse_area_id, $this->location->id]);
                $this->metaSection = 'warehouse';
                $this->sectionRoot = 'inventory.warehouses.show.areas.index';
                break;
            case 'warehouse':
                $this->set('editRoute', 'inventory.warehouses.show.locations.edit');
                $this->set('editRouteParameters', [$this->location->warehouse_id, $this->location->id]);
                $this->metaSection = 'warehouse';
                $this->sectionRoot = 'inventory.warehouses.show.locations.index';
                break;
            case 'tenant':
                $this->set('editRoute', 'inventory.locations.edit');
                $this->set('editRouteParameters', [$this->location->id]);
                $this->metaSection = 'warehouses';
                $this->sectionRoot = 'inventory.warehouses.index';

                break;
            case 'warehouseArea':

                $this->set('editRoute', 'inventory.areas.show.locations.edit');
                $this->set('editRouteParameters', [$this->location->warehouse_area_id, $this->location->id]);
                $this->metaSection = 'warehouses';
                $this->sectionRoot = 'inventory.warehouses.index';
                break;
        }
    }


    public function getBreadcrumbs(string $parent, Location $location): array
    {
        $commonItems = [
            'name'  => $location->code,
            'modelLabel'      => [
                'label' => __('location')
            ],
        ];

        return match ($parent) {
            'warehouseAreaInWarehouse' => array_merge(
                (new ShowWarehouseArea())->getBreadcrumbs('warehouse', $location->warehouseArea),
                [
                    'inventory.warehouses.show.areas.show.locations.show' =>
                        array_merge(
                            [
                                'route'           => 'inventory.warehouses.show.areas.show.locations.show',
                                'routeParameters' => [
                                    $location->warehouse_id,
                                    $location->warehouse_area_id,
                                    $location->id
                                ],
                                'index'           => [
                                    'route'           => 'inventory.warehouses.show.areas.show.locations.index',
                                    'routeParameters' => [
                                        $location->warehouse_id,
                                        $location->warehouse_area_id,
                                    ],
                                    'overlay'         => __('locations index')
                                ],
                            ],
                            $commonItems
                        ),
                ]
            ),
            'warehouse' => array_merge(
                (new ShowWarehouse())->getBreadcrumbs($location->warehouse),
                [
                    'inventory.warehouses.show.locations.show' =>
                        array_merge(
                            [
                                'route'           => 'inventory.warehouses.show.locations.show',
                                'routeParameters' => [
                                    $location->warehouse_id,
                                    $location->id
                                ],
                                'index'           => [
                                    'route'           => 'inventory.warehouses.show.locations.index',
                                    'routeParameters' => [
                                        $location->warehouse_id,
                                    ],
                                    'overlay'         => __('locations index')
                                ],
                            ],
                            $commonItems
                        ),
                ]
            ),
            'warehouseArea' => array_merge(
                (new ShowWarehouseArea())->getBreadcrumbs('warehouses', $location->warehouseArea),
                [
                    'inventory.areas.show.locations.show' =>
                        array_merge(
                            [
                                'route'           => 'inventory.areas.show.locations.show',
                                'routeParameters' => [
                                    $location->warehouse_area_id,
                                    $location->id
                                ],
                                'index'           => [
                                    'route'           => 'inventory.areas.show.locations.index',
                                    'routeParameters' => [
                                        $location->warehouse_area_id,
                                    ],
                                    'overlay'         => __('locations index')
                                ],
                            ],
                            $commonItems
                        ),
                ]
            ),
            default => array_merge(
                (new ShowInventoryDashboard())->getBreadcrumbs(),
                [
                    'inventory.locations.index' =>
                        array_merge([
                                        'route' => 'inventory.locations.index',
                                        'index'           => [
                                            'route'           => 'inventory.locations.index',
                                            'overlay'         => __('locations index')
                                        ],
                                    ], $commonItems),
                ]
            ),
        };
    }


}
