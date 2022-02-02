<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 13 Jan 2022 03:06:51 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\Warehouse;


use App\Http\Resources\Inventory\WarehouseInertiaResource;
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
class IndexWarehouse
{
    use AsAction;
    use WithInertia;



    public function handle(): LengthAwarePaginator
    {


        return QueryBuilder::for(Warehouse::class)
            ->select('warehouses.id','code','name','number_locations','number_warehouse_areas')
            ->leftJoin('warehouse_stats','warehouses.id','=','warehouse_stats.warehouse_id')
            ->allowedSorts(['code', 'name','number_warehouse_areas','number_locations'])
            ->paginate()
            ->withQueryString();
    }



    public function asInertia()
    {


        $this->validateAttributes();


        return Inertia::render(
            'Common/IndexModel',
            [
                'headerData' => [
                    'module'      => 'warehouses',
                    'title'       => $this->get('title'),
                    'breadcrumbs' => $this->getBreadcrumbs(),

                ],
                'dataTable'  => [
                    'records' => WarehouseInertiaResource::collection($this->handle()),
                    'columns' => [
                        'code' => [
                            'sort'  => 'code',
                            'label' => __('Code'),
                            'href'  => [
                                'route'  => 'warehouses.show',
                                'column' => 'id',
                                'with_permission'=>'can_view'
                            ],
                        ],
                        'name' => [
                            'sort'  => 'name',
                            'label' => __('Name')
                        ],
                        'number_warehouse_areas' => [
                            'sort'  => 'number_warehouse_areas',
                            'label' => __('Areas')
                        ],
                        'number_locations' => [
                            'sort'  => 'number_locations',
                            'label' => __('Locations'),
                            'href'  => [
                                'route'  => 'warehouses.show.locations.index',
                                'column' => 'id',
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
                'title' => __('Warehouses'),
            ]
        );
        $this->fillFromRequest($request);

    }


    public function getBreadcrumbs(): array
    {
        return [
            'index' => [
                'route'   => 'warehouses.index',
                'name'    => __('Warehouses'),
                'current' => false
            ],
        ];

    }


}
