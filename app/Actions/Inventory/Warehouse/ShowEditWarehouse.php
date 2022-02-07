<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 01 Feb 2022 14:50:51 Malaysia Time, Kuala Lumpur, Malaysia
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
 * @property string $parent
 */
class ShowEditWarehouse
{
    use AsAction;
    use WithInertia;

    public function handle()
    {
    }

    public function authorize(ActionRequest $request): bool
    {
        return  $request->user()->tokenCan('root')
            || $request->user()->tokenCan('inventory:edit')
            || $request->user()->hasPermissionTo("warehouses.edit.{$this->warehouse->id}");
    }

    public function asInertia(Warehouse $warehouse, array $attributes = []): Response
    {
        $this->set('warehouse', $warehouse)
            ->fill($attributes);
        $this->validateAttributes();

        $blueprint = [];

        $blueprint[] = [
            'title'    => __('Warehouse Id'),
            'subtitle' => '',
            'fields'   => [

                'code' => [
                    'type'    => 'input',
                    'label'   => __('Code'),
                    'value'   => $this->warehouse->code
                ],
                'name' => [
                    'type'    => 'input',
                    'label'   => __('Name'),
                    'value'   => $this->warehouse->name
                ],

            ]
        ];




        return Inertia::render(
            'edit-model',
            [
                'headerData' => [
                    'module'      => 'warehouses',
                    'title'       => __('Editing ').': '.$this->warehouse->code,
                    'breadcrumbs' => $this->getBreadcrumbs($this->warehouse),

                    'actionIcons' => [

                        'warehouses.show' => [
                            'routeParameters' => $this->warehouse->id,
                            'name'            => __('Exit edit'),
                            'icon'            => ['fal', 'portal-exit']
                        ],
                    ],



                ],
                'formData'    => [
                    'blueprint' => $blueprint,
                    'args'      => [
                        'postURL' => "/warehouses/{$this->warehouse->id}",
                    ]

                ],
            ]

        );


    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);


    }

    private function getBreadcrumbs( Warehouse $warehouse): array
    {


        return (new ShowWarehouse())->getBreadcrumbs($warehouse);
    }




}
