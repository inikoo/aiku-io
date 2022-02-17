<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 13 Jan 2022 03:19:10 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Marketing\Shop;

use App\Actions\UI\WithInertia;
use App\Models\Marketing\Shop;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


/**
 * @property Shop $shop
 */
class ShowShop
{
    use AsAction;
    use WithInertia;

    public function handle()
    {
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("shops.view.{$this->shop->id}");
    }

    public function asInertia(Shop $shop, array $attributes = []): Response
    {
        $this->set('shop', $shop)->set('type', 'fulfilment_house')->fill($attributes);

        $this->validateAttributes();


        session(['currentShop' => $shop->id]);


        return Inertia::render(
            'show-model',
            [
                'breadcrumbs' => $this->getBreadcrumbs($this->shop),
                'navData'     => ['module' => 'marketing', 'metaSection' => 'shop'],
                'headerData'  => [
                    'title' => $shop->name,
                    'meta'  => [
                        [
                            'icon' => ['fal', 'user'],
                            'name' => $shop->stats->number_customers,
                            'href' => [
                                'route'           => 'marketing.shops.show.customers.index',
                                'routeParameters' => $this->shop->id
                            ]
                        ],
                        [
                            'icon' => ['fal', 'shopping-cart'],
                            'name' => $shop->stats->number_orders,
                            'href' => [
                                'route'           => 'marketing.shops.show.orders.index',
                                'routeParameters' => $this->shop->id
                            ]
                        ],


                    ],

                ],
                'model'       => $shop
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);
    }

    public function getBreadcrumbs(Shop $shop): array
    {
        $breadcrumb = [
            'modelLabel'=>[
                'label'=>__('store')
            ],
            'route'           => 'marketing.shops.show',
            'routeParameters' => $shop->id,
            'name'            => $shop->code,
        ];

        if (session('marketingCount') > 1) {
            $breadcrumb['index'] = [
                'route'   => 'marketing.shops.index',
                'overlay' => __('Shops index')
            ];
        }

        return array_merge(
            session('marketingCount') == 1 ? [] : (new IndexShop())->getBreadcrumbs(),
            [
                'marketing.shops.show' => $breadcrumb
            ]
        );
    }

    public function asController(Shop $shop): Shop
    {
        $this->set('Shop', $shop);
        $this->validateAttributes();

        return $shop;
    }


}
