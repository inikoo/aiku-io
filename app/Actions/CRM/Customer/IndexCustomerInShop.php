<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 28 Jan 2022 15:17:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\CRM\Customer;


use App\Actions\Trade\Shop\ShowEcommerceShop;
use App\Http\Resources\CRM\CustomerInertiaResource;
use App\Models\CRM\Customer;
use App\Models\Trade\Shop;
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
 * @property \App\Models\Trade\Shop $shop
 * @property string $title
 */
class IndexCustomerInShop
{
    use AsAction;
    use WithInertia;

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("shops.customers.view") || $request->user()->hasPermissionTo("shops.customers.{$this->shop->id}.view");
    }


    public function handle(): LengthAwarePaginator
    {
        return QueryBuilder::for(Customer::class)
            ->select('id', 'name', 'shop_id')
            ->where('shop_id', $this->shop->id)
            ->defaultSorts('-id')
            ->allowedSorts(['name', 'id'])
            ->paginate()
            ->withQueryString();
    }


    public function asInertia(Shop $shop)
    {
        $this->set('shop', $shop);


        $this->validateAttributes();

        $breadcrumbs = $this->get('breadcrumbs');

        return Inertia::render(
            'Common/IndexModel',
            [
                'headerData' => [
                    'module'      => 'shops',
                    'title'       => $this->get('title'),
                    'breadcrumbs' => data_set($breadcrumbs, "index.current", true),

                ],
                'dataTable'  => [
                    'records' => CustomerInertiaResource::collection($this->handle()),
                    'columns' => [
                        'customer_number' => [
                            'sort'  => 'id',
                            'label' => __('Number'),
                            'href'  => [
                                'route'  => 'ecommerce_shops.show.customers.show',
                                'column' => ['shop_id', 'id']
                            ],
                        ],
                        'name'            => [
                            'sort'  => 'name',
                            'label' => __('Name')
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
                'title' => __('Customers in :shop', ['shop' => $this->shop->code])


            ]
        );
        $this->fillFromRequest($request);

        $this->set('breadcrumbs', $this->breadcrumbs());
    }


    private function breadcrumbs(): array
    {
        return array_merge(
            (new ShowEcommerceShop())->getBreadcrumbs(),
            [
                'ecommerce_shops.show.customers.index' => [
                    'route'           => 'ecommerce_shops.show.customers.index',
                    'routeParameters' => $this->shop->id,
                    'name'            => $this->title,
                    'current'         => false
                ],
            ]
        );
    }

    public function getBreadcrumbs(): array
    {
        $this->validateAttributes();

        return $this->breadcrumbs();
    }


}
