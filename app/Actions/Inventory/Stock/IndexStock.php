<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 20 Jan 2022 21:55:49 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\Stock;


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
            ->select(['id','code','description'])
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
            'Common/IndexModel',
            [
                'headerData' => [
                    'module'      => 'inventory',
                    'title'       => $this->get('title'),
                    'breadcrumbs' => $this->getBreadcrumbs()

                ],
                'dataTable'  => [
                    'records' => StockInertiaResource::collection($this->handle()),
                    'columns' => [
                        'code'        => [
                            'sort'  => 'code',
                            'label' => __('Code'),
                            'href'  => [
                                'route'  => 'inventory.stocks.show',
                                'column' => 'id'
                            ],
                        ],
                        'description' => [
                            'label' => __('Description')
                        ]
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
        return [
            'index' => [
                'route'   => 'warehouses.index',
                'name'    => __('Stocks'),
                'current' => false
            ],
        ];
    }


}
