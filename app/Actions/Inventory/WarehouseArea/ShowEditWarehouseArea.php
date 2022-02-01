<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 01 Feb 2022 13:15:35 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\WarehouseArea;

use App\Actions\UI\WithInertia;
use App\Models\Inventory\WarehouseArea;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


/**
 * @property \App\Models\Inventory\WarehouseArea $warehouseArea
 * @property string $parent
 */
class ShowEditWarehouseArea
{
    use AsAction;
    use WithInertia;

    public function handle()
    {
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root')
            || $request->user()->tokenCan('inventory:edit')
            || $request->user()->hasPermissionTo("warehouses.edit.{$this->warehouseArea->warehouse_id}");
    }

    public function asInertia(string $parent, WarehouseArea $warehouseArea, array $attributes = []): Response
    {
        $this->set('warehouseArea', $warehouseArea)
            ->set('parent',$parent)
            ->fill($attributes);
        $this->validateAttributes();

        $blueprint = [];

        $blueprint[] = [
            'title'    => __('Warehouse area Id'),
            'subtitle' => '',
            'fields'   => [

                'code' => [
                    'type'    => 'input',
                    'label'   => __('Code'),
                    'value'   => $this->warehouseArea->code
                ],
                'name' => [
                    'type'    => 'input',
                    'label'   => __('Name'),
                    'value'   => $this->warehouseArea->name
                ],

            ]
        ];


        return Inertia::render(
            'Common/EditModel',
            [
                'headerData' => [
                    'module'      => 'warehouses',
                    'title'       => __('Editing ').': '.$this->warehouseArea->code,
                    'breadcrumbs' => $this->getBreadcrumbs($this->parent,$this->warehouseArea),

                    'actionIcons' => [

                        $this->get('showRoute') => [
                            'routeParameters' => $this->get('showRouteParameters'),
                            'name'            => __('Exit edit'),
                            'icon'            => ['fal', 'portal-exit']
                        ],
                    ],



                ],
                'employee'       => $this->warehouseArea,
                'formData'    => [
                    'blueprint' => $blueprint,
                    'args'      => [
                        'postURL' => "/warehouses/areas/{$this->warehouseArea->id}",
                    ]

                ],
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);

        switch ($this->parent) {

            case 'warehouse':
                $this->set('showRoute', 'warehouses.show.areas.show');
                $this->set('showRouteParameters',[$this->warehouseArea->warehouse_id, $this->warehouseArea->id]);
                break;
            case 'tenant':
                $this->set('showRoute', 'warehouses.areas.show');
                $this->set('showRouteParameters', [$this->warehouseArea->id]);

                break;

        }
    }

    private function getBreadcrumbs(string $parent, WarehouseArea $warehouseArea): array
    {


        return (new ShowWarehouseArea())->getBreadcrumbs($parent,$warehouseArea);
    }




}
