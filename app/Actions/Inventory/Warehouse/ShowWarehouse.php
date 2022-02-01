<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 16 Jan 2022 12:31:52 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\Warehouse;


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
        $this->set('warehouse', $warehouse)->set('module', 'warehouse')->fill($attributes);

        $this->validateAttributes();


        session(['currentWarehouse' => $warehouse->id]);


        return Inertia::render(
            'Common/ShowModel',
            [
                'headerData' => [
                    'module'      => 'warehouses',
                    'title'       => $warehouse->name,
                    'breadcrumbs' => $this->getBreadcrumbs($this->warehouse),

                ],
                'model'      => $warehouse
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);
    }


    public function getBreadcrumbs(Warehouse $warehouse): array
    {
        return array_merge(
            (new IndexWarehouse())->getBreadcrumbs(),
            [
                'warehouses.show' => [
                    'route'           => 'warehouses.show',
                    'routeParameters' => $warehouse->id,
                    'name'            => $warehouse->code,
                    'model'           => [
                        'label' => __('Warehouse'),
                        'icon'  => ['fal', 'warehouse-alt'],
                    ],

                ],
            ]
        );
    }


}
