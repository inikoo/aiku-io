<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 16 Jan 2022 12:31:52 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\Warehouse;


use App\Actions\Inventory\ShowInventoryDashboard;
use App\Actions\UI\WithInertia;
use App\Models\Inventory\Warehouse;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


/**
 * @property Warehouse $warehouse
 */
class ShowWarehouse
{
    use AsAction;
    use WithInertia;


    public function handle()
    {
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("warehouses.view.{$this->warehouse->id}");
    }


    public function asInertia(Warehouse $warehouse, array $attributes = []): Response
    {
        $this->set('warehouse', $warehouse)->fill($attributes);

        $this->validateAttributes();


        session(['currentWarehouse' => $warehouse->id]);


        $actionIcons = [];
        if ($this->get('canEdit')) {
            $actionIcons['inventory.warehouses.edit'] = [
                'routeParameters' => $this->warehouse->id,
                'name'            => __('Edit'),
                'icon'            => ['fal', 'edit']
            ];
        }


        return Inertia::render(
            'show-model',
            [
                'breadcrumbs' => $this->getBreadcrumbs($this->warehouse),
                'navData'     => ['module' => 'inventory', 'metaSection' => 'warehouse','sectionRoot'=>'inventory.warehouses.show'],

                'headerData' => [
                    'title'       => $warehouse->name,
                    'actionIcons' => $actionIcons,

                ],
                'model'      => $warehouse
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);
        $this->set('canEdit', $request->user()->can("warehouses.edit.{$this->warehouse->id}"));

    }


    public function getBreadcrumbs(Warehouse $warehouse): array
    {
        $breadcrumb = [
            'modelLabel'      => [
                'label' => __('warehouse')
            ],
            'route'           => 'inventory.warehouses.show',
            'routeParameters' => $warehouse->id,
            'name'            => $warehouse->code,
        ];

        if (session('inventoryCount') > 1) {
            $breadcrumb['index'] = [
                'route'   => 'inventory.warehouses.index',
                'overlay' => __('Warehouses index')
            ];
        }

        return array_merge(
            session('marketingCount') == 1 ? [] : (new ShowInventoryDashboard())->getBreadcrumbs(),
            [
                'inventory.warehouses.show' => $breadcrumb
            ]
        );



    }


}
