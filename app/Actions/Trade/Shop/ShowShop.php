<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 13 Jan 2022 03:19:10 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Trade\Shop;

use App\Actions\UI\WithInertia;
use App\Models\Trade\Shop;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;


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
                'navLocation' => ['module' => 'shops', 'metaSection' => 'shop'],
                'headerData' => [
                    'title'  => $shop->name,

                ],
                'model'      => $shop
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);
    }

    public function getBreadcrumbs(Shop $shop): array
    {
        return array_merge(
            (new IndexShop())->getBreadcrumbs(),
            [
                'shops.show' => [
                    'route' => 'shops.show',
                    'routeParameters' => $shop->id,
                    'name' => $shop->code,
                ],
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
