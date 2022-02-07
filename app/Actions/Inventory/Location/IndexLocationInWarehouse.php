<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 29 Jan 2022 01:55:22 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\Location;


use App\Actions\Inventory\Warehouse\ShowWarehouse;
use App\Http\Resources\Inventory\LocationInertiaResource;
use App\Models\Inventory\Location;
use App\Models\Inventory\Warehouse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\QueryBuilder;

use App\Actions\UI\WithInertia;

use function __;

/**
 * @property Warehouse $warehouse
 */
class IndexLocationInWarehouse
{
    use AsAction;
    use WithInertia;


    public function handle(): LengthAwarePaginator
    {
        return QueryBuilder::for(Location::class)
            ->select(
                'locations.id as id',
                'locations.code as code',
                'locations.warehouse_id',
                'locations.warehouse_area_id',
                'warehouse_areas.code as warehouse_area_code'
            )
            ->leftJoin('warehouse_areas', 'locations.warehouse_area_id', '=', 'warehouse_areas.id')
            ->where('locations.warehouse_id', $this->warehouse->id)
            ->allowedSorts(['code'])
            ->paginate()
            ->withQueryString();
    }


    public function asInertia(Warehouse $warehouse)
    {
        $this->set('warehouse', $warehouse);

        $this->validateAttributes();


        return Inertia::render(
            'index-model',
            [
                'headerData' => [
                    'module'      => 'warehouses',
                    'title'       => $this->get('title'),
                    'breadcrumbs' => $this->getBreadcrumbs($this->warehouse),

                ],
                'dataTable'  => [
                    'records' => LocationInertiaResource::collection($this->handle()),
                    'columns' => [
                        'code' => [
                            'sort'  => 'code',
                            'label' => __('Code'),
                            'href'  => [
                                'route'  => 'warehouses.show.locations.show',
                                'column' => ['warehouse_id', 'id'],
                                'if'     => 'id'
                            ],
                        ],

                        'warehouse_area_code' => [
                            'label' => __('Warehouse area'),
                            'href'  => [
                                'route'       => 'warehouses.show.areas.show',
                                'column'      => ['warehouse_id', 'warehouse_area_id'],
                                'if'          => 'warehouse_area_id',
                                'notSetLabel' => __('Not set')
                            ],
                        ],

                    ]
                ]


            ]
        )->table(function (InertiaTable $table) {
        });
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'title' => __('Locations in :warehouse', ['warehouse' => $this->warehouse->code]),


            ]
        );
        $this->fillFromRequest($request);

    }


    public function getBreadcrumbs(Warehouse $warehouse): array
    {
        return array_merge(
            (new ShowWarehouse())->getBreadcrumbs($warehouse),
            [
                'warehouses.show.locations.index' => [
                    'route'           => 'warehouses.show.locations.index',
                    'routeParameters' => $warehouse->id,
                    'name'            => __('Locations'),
                    'current'         => false
                ]
            ]
        );
    }




}
