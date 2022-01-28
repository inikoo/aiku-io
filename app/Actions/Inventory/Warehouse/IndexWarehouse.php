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
use function data_set;

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
            ->select('id','code','name')
            ->allowedSorts(['code', 'name'])
            ->paginate()
            ->withQueryString();
    }



    public function asInertia()
    {


        $this->validateAttributes();

        $breadcrumbs = $this->get('breadcrumbs');

        return Inertia::render(
            'Common/IndexModel',
            [
                'headerData' => [
                    'module'      => 'warehouses',
                    'title'       => $this->get('title'),
                    'breadcrumbs' => data_set($breadcrumbs, "index.current", true),

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
                        ]
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

        $this->set('breadcrumbs',$this->breadcrumbs());


    }


    private function breadcrumbs(): array
    {

        return [
            'index' => [
                'route'   => 'warehouses.index',
                'name'    => $this->get('title'),
                'current' => false
            ],
        ];
    }

    public function getBreadcrumbs(): array
    {
        $this->validateAttributes();
        return $this->breadcrumbs();

    }


}
