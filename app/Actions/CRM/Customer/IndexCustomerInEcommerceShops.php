<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 28 Jan 2022 19:09:22 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\CRM\Customer;


use App\Actions\Trade\Shop\IndexEcommerceShop;
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
 * @property string $title
 * @property array $allowed_shops
 */
class IndexCustomerInEcommerceShops
{
    use AsAction;
    use WithInertia;

    public function authorize(ActionRequest $request): bool
    {


        $can_view= $request->user()->hasPermissionTo("shops.customers.view");

        if(!$can_view){
            $this->allowed_shops=Shop::withTrashed()->get()->pluck('id');
            $permissions=$this->allowed_shops->map(function ($shopID) {
                return "shops.customers.$shopID.view";
            });
            $can_view=$request->user()->hasAnyPermission($permissions);

        }

        return $can_view;
    }

    public function handle(): LengthAwarePaginator
    {

        return QueryBuilder::for(Customer::class)
            ->select('id', 'name', 'shop_id')

            ->when(!is_null($this->allowed_shops), function ($query) {
                return $query->whereIn(
                    'shop_id',$this->allowed_shops

                );
            })
            ->with('shop')
            ->defaultSorts('-id')
            ->allowedSorts(['name', 'id'])
            ->paginate()
            ->withQueryString();
    }


    public function asInertia()
    {
        $this->allowed_shops=null;
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
                    'records' => CustomerInertiaResource::collection($this->handle()),
                    'columns' => [
                        'shop_code' => [
                            'label' => __('Shop'),
                            'href'  => [
                                'route'  => 'ecommerce_shops.show.customers.index',
                                'column' => 'shop_id'
                            ],
                        ],
                        'customer_number' => [
                            'sort'  => 'customer_number',
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
                'title' => is_null($this->allowed_shops)? __('Customers in all ecommerce shops'):__('Customers in visible ecommerce shops')
            ]
        );
        $this->fillFromRequest($request);

        $this->set('breadcrumbs', $this->breadcrumbs());
    }


    private function breadcrumbs(): array
    {
        return array_merge(
            (new IndexEcommerceShop())->getBreadcrumbs(),
            [
                'ecommerce_shops.customers.index' => [
                    'route'   => 'ecommerce_shops.customers.index',
                    'name'    => __('Customers'),
                    'current' => false
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
