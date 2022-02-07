<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 01 Feb 2022 02:31:06 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\Location;

use App\Actions\UI\WithInertia;
use App\Models\Inventory\Location;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


/**
 * @property Location $location
 * @property string $parent
 */
class ShowEditLocation
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
            || $request->user()->hasPermissionTo("warehouses.edit.{$this->location->warehouse_id}");
    }

    public function asInertia(string $parent, Location $location, array $attributes = []): Response
    {
        $this->set('location', $location)
            ->set('parent', $parent)
            ->fill($attributes);
        $this->validateAttributes();

        $blueprint = [];

        $blueprint[] = [
            'title'    => __('Location Id'),
            'subtitle' => '',
            'fields'   => [

                'code' => [
                    'type'  => 'input',
                    'label' => __('Code'),
                    'value' => $this->location->code
                ],

            ]
        ];


        return Inertia::render(
            'edit-model',
            [
                'headerData' => [
                    'module'      => 'warehouses',
                    'title'       => __('Editing').': '.$this->location->code,
                    'breadcrumbs' => $this->getBreadcrumbs($this->parent, $this->location),

                    'actionIcons' => [

                        $this->get('showRoute') => [
                            'routeParameters' => $this->get('showRouteParameters'),
                            'name'            => __('Exit edit'),
                            'icon'            => ['fal', 'portal-exit']
                        ],
                    ],


                ],
                'employee'   => $this->location,
                'formData'   => [
                    'blueprint' => $blueprint,
                    'args'      => [
                        'postURL' => "/warehouses/locations/{$this->location->id}",
                    ]

                ],
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);

        switch ($this->parent) {
            case 'warehouseArea':
                $this->set('showRoute', 'warehouses.show.areas.show.locations.show');
                $this->set('showRouteParameters', [$this->location->warehouse_id, $this->location->warehouse_area_id, $this->location->id]);
                break;
            case 'warehouse':
                $this->set('showRoute', 'warehouses.show.locations.show');
                $this->set('showRouteParameters', [$this->location->warehouse_id, $this->location->id]);
                break;
            case 'tenant':
                $this->set('showRoute', 'warehouses.locations.show');
                $this->set('showRouteParameters', [$this->location->id]);
                break;
        }
    }

    private function getBreadcrumbs(string $parent, Location $location): array
    {
        return (new ShowLocation())->getBreadcrumbs($parent, $location);
    }


}
