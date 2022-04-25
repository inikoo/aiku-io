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
 * @property string $module
 * @property string $metaSection
 * @property string $sectionRoot
 */
class ShowEditLocation
{
    use AsAction;
    use WithInertia;

    public function handle(): void
    {
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root')
            || $request->user()->tokenCan('inventory:edit')
            || $request->user()->hasPermissionTo("warehouses.edit.{$this->location->warehouse_id}");
    }

    public function asInertia(string $parent, Location $location): Response
    {
        $this->set('location', $location)
            ->set('parent', $parent);
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
                'breadcrumbs' => $this->getBreadcrumbs($this->parent, $this->location),
                'navData'     => ['module' => $this->module, 'metaSection' => $this->metaSection, 'sectionRoot' => $this->sectionRoot],
                'headerData'  => [
                    'title' => __('Editing').': '.$this->location->code,

                    'actionIcons' => [

                        [
                            'route'           => $this->get('showRoute'),
                            'routeParameters' => $this->get('showRouteParameters'),
                            'name'            => __('Exit edit'),
                            'icon'            => ['fal', 'portal-exit']
                        ],
                    ],


                ],
                'employee'    => $this->location,
                'formData'    => [
                    'blueprint' => $blueprint,
                    'args'      => [
                        'postURL' => route(
                            'inventory.locations.update',
                            [
                                $this->location->id
                            ]
                        )
                    ]

                ],
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);

        $this->module = 'inventory';

        switch ($this->parent) {
            case 'warehouseAreaInWarehouse':
                $this->set('showRoute', 'inventory.warehouses.show.areas.show.locations.show');
                $this->set('showRouteParameters', [$this->location->warehouse_id, $this->location->warehouse_area_id, $this->location->id]);
                $this->metaSection = 'warehouse';
                $this->sectionRoot = 'inventory.warehouses.show.areas.index';
                break;
            case 'warehouse':
                $this->set('showRoute', 'inventory.warehouses.show.locations.show');
                $this->set('showRouteParameters', [$this->location->warehouse_id, $this->location->id]);
                $this->metaSection = 'warehouse';
                $this->sectionRoot = 'inventory.warehouses.show.locations.index';
                break;
            case 'tenant':
                $this->set('showRoute', 'inventory.locations.show');
                $this->set('showRouteParameters', [$this->location->id]);
                $this->metaSection = 'warehouses';
                $this->sectionRoot = 'inventory.warehouses.index';
                break;
            case 'warehouseArea':
                $this->set('showRoute', 'inventory.areas.show.locations.show');
                $this->set('showRouteParameters', [$this->location->warehouse_area_id, $this->location->id]);
                $this->metaSection = 'warehouses';
                $this->sectionRoot = 'inventory.warehouses.index';
                break;
        }
    }

    private function getBreadcrumbs(string $parent, Location $location): array
    {
        return (new ShowLocation())->getBreadcrumbs($parent, $location);
    }


}
