<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 31 Jan 2022 04:21:07 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\WarehouseArea;


use App\Actions\Inventory\ShowInventoryDashboard;
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
            $actionIcons[] = [
                'route'           => $this->get('editRoute'),
                'routeParameters' => $this->get('editRouteParameters'),
                'name'            => __('Edit'),
                'icon'            => ['fal', 'edit']
            ];
        }


        return Inertia::render(
            'show-model',
            [
                'breadcrumbs' => $this->getBreadcrumbs($this->parent, $warehouseArea),
                'navData'     => [
                    'module'      => 'inventory',
                    'metaSection' => $this->parent == 'warehouse' ? 'warehouse' : 'warehouses',
                    'sectionRoot' => $this->parent == 'warehouse' ? 'inventory.warehouses.show.areas.index' : 'inventory.warehouses.index'
                ],
                'headerData'  => [
                    'module' => 'warehouses',
                    'title'  => __('Warehouse area').': '.$warehouseArea->name,

                    'actionIcons' => $actionIcons,
                    'info'        => [
                        [
                            'type' => 'group',
                            'data' => [
                                'components' => [
                                    [
                                        'type' => 'icon',
                                        'data' => array_merge(
                                            [
                                                'type' => 'page-header',
                                                'icon' => ['fal', 'inventory']
                                            ],

                                        )
                                    ],
                                    [
                                        'type' => 'number',
                                        'data' => [
                                            'slot' => $this->warehouseArea->stats->number_locations
                                        ]
                                    ],
                                    [
                                        'type' => 'link',
                                        'data' => [
                                            'slot'  => __('locations'),
                                            'class' => ' ml-1',
                                            'href'  =>
                                                match ($this->parent) {
                                                    'warehouse' =>
                                                    [
                                                        'route'      => 'inventory.warehouses.show.areas.show.locations.index',
                                                        'parameters' => [
                                                            $this->warehouseArea->warehouse_id,
                                                            $this->warehouseArea->id
                                                        ]
                                                    ],
                                                    default =>
                                                    [
                                                        'route'      => 'inventory.areas.show.locations.index',
                                                        'parameters' => [
                                                            $this->warehouseArea->id
                                                        ]
                                                    ]
                                                }


                                        ]
                                    ],

                                ]
                            ]
                        ]
                    ]

                ],
                'model'       => $warehouseArea
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);
        $this->set('canEdit', $request->user()->can("warehouses.edit.{$this->warehouseArea->warehouse_id}"));

        switch ($this->parent) {
            case 'warehouse':
                $this->set('editRoute', 'inventory.warehouses.show.areas.edit');
                $this->set('editRouteParameters', [$this->warehouseArea->warehouse_id, $this->warehouseArea->id]);

                break;
            case 'tenant':
                $this->set('editRoute', 'inventory.areas.edit');
                $this->set('editRouteParameters', [$this->warehouseArea->id]);

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
                    'inventory.warehouses.show.areas.show' =>
                        array_merge(
                            [
                                'route'           => 'inventory.warehouses.show.areas.show',
                                'routeParameters' => [
                                    $warehouseArea->warehouse_id,
                                    $warehouseArea->id
                                ],
                                'index'           => [
                                    'route'           => 'inventory.warehouses.show.areas.index',
                                    'routeParameters' => [
                                        $warehouseArea->warehouse_id,
                                    ],
                                    'overlay'         => __('warehouse areas index')
                                ],
                                'modelLabel'      => [
                                    'label' => __('area')
                                ],
                            ],
                            $commonItems
                        ),
                ]
            );
        } else {
            return array_merge(
                (new ShowInventoryDashboard())->getBreadcrumbs(),
                [
                    'inventory.areas.index' =>
                        array_merge(
                            [
                                'index'           => [
                                    'route'   => 'inventory.areas.index',
                                    'overlay' => __(
                                        'warehouse areas index'
                                    )
                                ],
                                'route'           => 'inventory.areas.show',
                                'routeParameters' => [
                                    $warehouseArea->id
                                ],
                                'modelLabel'      => [
                                    'label' => __(
                                        'areas'
                                    )
                                ],

                            ],
                            $commonItems
                        ),
                ]
            );
        }
    }


}
