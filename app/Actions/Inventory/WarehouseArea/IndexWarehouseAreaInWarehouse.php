<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 31 Jan 2022 03:39:55 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\WarehouseArea;


use App\Actions\Inventory\Warehouse\ShowWarehouse;
use App\Http\Resources\Inventory\WarehouseAreaInertiaResource;
use App\Models\Inventory\Warehouse;
use App\Models\Inventory\WarehouseArea;
use App\Traits\HasDBDriverAwareQueries;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

use App\Actions\UI\WithInertia;

use function __;

/**
 * @property Warehouse $warehouse
 */
class IndexWarehouseAreaInWarehouse
{
    use AsAction;
    use WithInertia;
    use HasDBDriverAwareQueries;


    public function handle(): LengthAwarePaginator
    {

        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('warehouse_areas.code', $this->likeOperator(), "$value%")
                    ->orWhere('warehouse_areas.name', $this->likeOperator(), "%$value%");
            });
        });


        return QueryBuilder::for(WarehouseArea::class)
            ->select('warehouse_areas.id as id', 'code', 'warehouse_id', 'number_locations', 'name')
            ->leftJoin('warehouse_area_stats', 'warehouse_areas.id', '=', 'warehouse_area_stats.warehouse_area_id')
            ->where('warehouse_id', $this->warehouse->id)
            ->allowedSorts(['code', 'name', 'number_locations'])
            ->allowedFilters([$globalSearch])
            ->paginate()
            ->withQueryString();
    }


    public function getUnfilteredCount()
    {

    }


    public function asInertia(Warehouse $warehouse)
    {
        $this->set('warehouse', $warehouse);

        $this->validateAttributes();

        $breadcrumbs = $this->get('breadcrumbs');

        return Inertia::render(
            'Common/IndexModel',
            [
                'headerData' => [
                    'module'      => 'warehouses',
                    'title'       => $this->get('title'),
                    'breadcrumbs' => $breadcrumbs,

                ],
                'dataTable'  => [
                    'records' => WarehouseAreaInertiaResource::collection($this->handle()),
                    'columns' => [
                        'code' => [
                            'sort'  => 'code',
                            'label' => __('Code'),
                            'href'  => [
                                'route'  => 'warehouses.show.areas.show',
                                'column' => ['warehouse_id', 'id'],
                                'if'     => 'id'
                            ],
                        ],
                        'name' => [
                            'sort'  => 'name',
                            'label' => __('Name')
                        ],

                        'number_locations' => [
                            'sort'  => 'number_locations',
                            'label' => __('Locations'),
                            'href'  => [
                                'route'  => 'warehouses.show.areas.show.locations.index',
                                'column' => ['warehouse_id', 'id'],
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
                'title' => __('Areas in :warehouse', ['warehouse' => $this->warehouse->code]),


            ]
        );
        $this->fillFromRequest($request);

        $this->set('breadcrumbs', $this->breadcrumbs());
    }


    private function breadcrumbs(): array
    {
        return array_merge(
            (new ShowWarehouse())->getBreadcrumbs($this->warehouse),
            [
                'warehouses.show.areas.index' => [
                    'route'           => 'warehouses.show.areas.index',
                    'routeParameters' => $this->warehouse->id,
                    'name'            => __('Areas'),
                    'current'         => false
                ]
            ]
        );
    }

    public function getBreadcrumbs(): array
    {
        $this->validateAttributes();

        return $this->breadcrumbs();
    }


}
