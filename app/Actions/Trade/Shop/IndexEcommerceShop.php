<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 28 Jan 2022 16:03:09 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Trade\Shop;


use App\Http\Resources\Trade\ShopInertiaResource;
use App\Models\Trade\Shop;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\QueryBuilder;

use App\Actions\UI\WithInertia;

use function __;

/**
 * @property Shop $shop
 * @property array $breadcrumbs
 */
class IndexEcommerceShop
{
    use AsAction;
    use WithInertia;


    public function handle(): LengthAwarePaginator
    {
        return QueryBuilder::for(Shop::class)
            ->where('type', 'shop')
            ->allowedSorts(['code', 'name'])
            ->paginate()
            ->withQueryString();
    }




    public function asInertia()
    {
        $this->validateAttributes();


        return Inertia::render(
            'index-model',
            [
                'headerData' => [
                    'module'      => 'ecommerce',
                    'title'       => $this->get('title'),
                    'breadcrumbs' => $this->getBreadcrumbs(),

                ],
                'dataTable'  => [
                    'records' => ShopInertiaResource::collection($this->handle()),
                    'columns' => [
                        'code' => [
                            'sort'  => 'code',
                            'label' => __('Code'),
                            'href'  => [
                                'route'  => 'ecommerce_shops.show',
                                'column' => 'id',
                                'with_permission'=>'can_view'
                            ],
                        ],
                        'name' => [
                            'sort'  => 'name',
                            'label' => __('Name')
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
                'title' => __('Ecommerce shops')
            ]
        );
        $this->fillFromRequest($request);

    }


    public function getBreadcrumbs(): array
    {
        return [
            'ecommerce_shops.index' => [
                'route'   => 'ecommerce_shops.index',
                'name'    => __('Ecommerce shops'),
                'current' => false
            ],
        ];
    }




}
