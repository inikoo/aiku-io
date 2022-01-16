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
        return $request->user()->hasPermissionTo("warehouses.{$this->warehouse->id}.view");
    }


    public function asInertia(Warehouse $warehouse, array $attributes = []): Response
    {
        $this->set('warehouse', $warehouse)->set('module', 'warehouse')->fill($attributes);

        $this->validateAttributes();


        session(['currentWarehouse' => $warehouse->id]);


        return Inertia::render(
            $this->get('page'),
            [
                'headerData' => [
                    'module'      => 'warehouses',
                    'title'       => $warehouse->name,
                    'breadcrumbs' => $this->get('breadcrumbs'),

                ],
                'warehouse'  => $warehouse
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'page' => 'Warehouses/Warehouse',
            ]
        );
        $this->fillFromRequest($request);

        $this->set('breadcrumbs', $this->breadcrumbs());
    }


    private function breadcrumbs(): array
    {
        /** @var Warehouse $warehouse */
        $warehouse = $this->get('warehouse');


        return array_merge(
            (new IndexWarehouse())->getBreadcrumbs(),
            [
                'warehouse' => [
                    'route'           => 'warehouses.show',
                    'routeParameters' => $warehouse->id,
                    'name'            => $warehouse->code,
                    'current'         => false
                ],
            ]
        );
    }


}
