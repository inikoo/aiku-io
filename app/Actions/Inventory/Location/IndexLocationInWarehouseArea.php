<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 31 Jan 2022 04:16:17 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\Location;


use App\Actions\Inventory\WarehouseArea\ShowWarehouseArea;
use App\Http\Resources\Inventory\LocationInertiaResource;
use App\Models\Inventory\Location;
use App\Models\Inventory\Warehouse;
use App\Models\Inventory\WarehouseArea;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\QueryBuilder;

use App\Actions\UI\WithInertia;

use function __;

/**
 * @property WarehouseArea $warehouseArea
 */
class IndexLocationInWarehouseArea
{
    use AsAction;
    use WithInertia;


    public function handle(): LengthAwarePaginator
    {
        return QueryBuilder::for(Location::class)
            ->select(
                'id',
                'code',
                'warehouse_id',
                'warehouse_area_id',
            )
            ->where('warehouse_area_id', $this->warehouseArea->id)
            ->allowedSorts(['code'])
            ->paginate()
            ->withQueryString();
    }


    public function asInertia(Warehouse $warehouse, WarehouseArea $warehouseArea)
    {
        $this->set('warehouse', $warehouse);
        $this->set('warehouseArea', $warehouseArea);

        $this->validateAttributes();


        return Inertia::render(
            'Common/IndexModel',
            [
                'headerData' => [
                    'module'      => 'warehouses',
                    'title'       => $this->get('title'),
                    'breadcrumbs' => $this->getBreadcrumbs($this->warehouseArea),

                ],
                'dataTable'  => [
                    'records' => LocationInertiaResource::collection($this->handle()),
                    'columns' => [
                        'code' => [
                            'sort'  => 'code',
                            'label' => __('Code'),
                            'href'  => [
                                'route'  => 'warehouses.show.areas.show.locations.show',
                                'column' => ['warehouse_id', 'warehouse_area_id', 'id'],
                                'if'     => 'id'
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
                'title' => __('Locations in :warehouse', ['warehouse' => $this->warehouseArea->code]),


            ]
        );
        $this->fillFromRequest($request);
    }


    public function getBreadcrumbs(WarehouseArea $warehouseArea): array
    {
        return array_merge(
            (new ShowWarehouseArea())->getBreadcrumbs('warehouse', $warehouseArea),
            [
                'warehouses.show.locations.index' => [
                    'route'           => 'warehouses.show.areas.show.locations.index',
                    'routeParameters' => [$warehouseArea->warehouse_id, $warehouseArea->id],
                    'name'            => __('Locations'),
                    'current'         => false
                ]
            ]
        );
    }


}
