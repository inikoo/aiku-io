<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 20 Jan 2022 22:48:06 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\CRM\Customer;


use App\Models\CRM\Customer;
use App\Models\Inventory\Stock;
use App\Models\Inventory\Warehouse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\QueryBuilder;

use App\Actions\UI\WithInertia;

use function __;
use function data_set;


class IndexCustomerInTenant
{
    use AsAction;
    use WithInertia;


    public function handle(): LengthAwarePaginator
    {
        return QueryBuilder::for(Customer::class)
            ->select('id', 'shop_id', 'name', 'location', '')
            ->with('shop')
            ->allowedSorts(['name', 'id', 'location'])
            ->paginate()
            ->withQueryString();
    }


    public function asInertia(string $parent)
    {
        $this->set('parent',$parent);

        $this->validateAttributes();

        $breadcrumbs = $this->get('breadcrumbs');

        return Inertia::render(
            'index-model',
            [
                'headerData' => [
                    'module'      => 'shops',
                    'title'       => $this->get('title'),
                    'breadcrumbs' => data_set($breadcrumbs, "index.current", true),

                ],
                'dataTable'  => [
                    'records' => $this->handle(),
                    'columns' => [
                        'code'     => [
                            'sort'  => 'code',
                            'label' => __('Code'),
                            'href'  => [
                                'route'  => 'inventory.stocks.show',
                                'column' => 'id'
                            ],
                        ],
                        'name'     => [
                            'sort'  => 'description',
                            'label' => __('Description')
                        ],
                        'location'                   => [
                            'label' => __('Location'),
                            'location'=>true,
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
                'title' => match ($this->routeName) {
                    'ecommerce_shops.show.customers.index' => __('Customers', ['store' => $this->parent->code]),
                    default => __('Customers'),
                }


            ]
        );
        $this->fillFromRequest($request);

        $this->set('breadcrumbs', $this->breadcrumbs());
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
