<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 20 Jan 2022 21:55:49 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\Stock;


use App\Actions\Inventory\ShowInventoryDashboard;
use App\Http\Resources\Inventory\StockInertiaResource;
use App\Models\Inventory\Stock;
use App\Models\Inventory\Warehouse;
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
class IndexStock
{
    use AsAction;
    use WithInertia;


    public function handle(): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('description', 'LIKE', "%$value%")
                    ->orWhere('code', 'LIKE', "$value%");
            });
        });

        return QueryBuilder::for(Stock::class)
            ->select(['id', 'code', 'description'])
            ->allowedSorts(['code'])
            ->defaultSort('-id')
            ->allowedFilters([$globalSearch])
            ->paginate()
            ->withQueryString();
    }


    public function asInertia()
    {
        $this->validateAttributes();


        return Inertia::render(
            'index-model',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'navData'     => [
                    'module'      => 'inventory',
                    'metaSection' => session('currentWarehouse') ? 'warehouse' : 'warehouses',
                    'sectionRoot' => 'inventory.stocks.index'
                ],
                'headerData'  => [
                    'title' => $this->get('title'),

                ],
                'dataTable'   => [
                    'records' => StockInertiaResource::collection($this->handle()),
                    'columns' => [

                        'code'        => [
                            'sort'       => 'code',
                            'label'      => __('Code'),
                            'components' => [
                                [
                                    'type'     => 'link',
                                    'resolver' => [
                                        'type'       => 'link',
                                        'parameters' => [
                                            'href'    => [
                                                'route'   => 'inventory.stocks.show',
                                                'indices' => 'id'
                                            ],
                                            'indices' => 'code'
                                        ],
                                    ]
                                ]
                            ],
                        ],
                        'description' => [
                            'label'    => __('Description'),
                            'resolver' => 'description'
                        ],

                    ]
                ]


            ]
        )->table(function (InertiaTable $table) {
            $table->addSearchRows(
                [


                ]
            );
        });
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'title' => __('Stocks'),


            ]
        );
        $this->fillFromRequest($request);
    }


    public function getBreadcrumbs(): array
    {
        return array_merge(
            (new ShowInventoryDashboard())->getBreadcrumbs(),
            [
                'inventory.stocks.index' => [
                    'route'      => 'inventory.stocks.index',
                    'modelLabel' => [
                        'label' => __('stocks')
                    ],
                ],
            ]
        );
    }


}

