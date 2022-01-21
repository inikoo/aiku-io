<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 20 Jan 2022 21:55:49 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\Stock;


use App\Models\Inventory\Stock;
use App\Models\Inventory\Warehouse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

use App\Actions\UI\WithInertia;

use function __;
use function data_set;

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
            ->when($this->get('routeName'), function ($query, $routeName) {


                switch ($routeName){
                    case 'shops.customers.index':
                        return $query->where(
                            'vendor_type','Shop'
                        );
                    default:
                        return false;
                }


            })
            ->allowedSorts(['code'])
            ->defaultSort('-id')
            ->allowedFilters([ $globalSearch])
            ->paginate()
            ->withQueryString();
    }



    public function asInertia(Request $request)
    {

        $this->set('routeName',$request->route()->getName());
        $this->set('routeParameters',$request->route()->parameters());


        $this->validateAttributes();

        $breadcrumbs = $this->get('breadcrumbs');

        return Inertia::render(
            'Common/IndexModel',
            [
                'headerData' => [
                    'module'      => 'inventory',
                    'title'       => $this->get('title'),
                    'breadcrumbs' => data_set($breadcrumbs, "index.current", true),

                ],
                'dataTable'  => [
                    'records' => $this->handle(),
                    'columns' => [
                        'code' => [
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
