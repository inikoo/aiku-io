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
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


/**
 * @property Shop $shop
 * @property string $module
 * @property string $type
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
        return $request->user()->hasPermissionTo("shops.{$this->shop->id}.view");
    }

    public function afterValidator(): void
    {
        if ($this->shop->type != $this->type) {
            abort(422, "Store is not the correct type $this->type, has {$this->shop->type} ");
        }
    }


    public function asInertia(Shop $shop, string $module, array $attributes = []): Response
    {
        $this->set('shop', $shop)->set('module', $module)->fill($attributes);

        $this->validateAttributes();


        session(['current'.ucfirst($module) => $shop->id]);


        return Inertia::render(
            $this->get('page'),
            [
                'headerData' => [
                    'module'      => $this->module,
                    'title'       => $shop->name,
                    'breadcrumbs' => $this->get('breadcrumbs'),

                ],
                'shop'       => $shop
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'page' => match ($this->module) {
                    'fulfilment_houses' => 'FulfilmentHouses/FulfilmentHouse',
                    default => 'Shops/Shop',
                },
                'type' => $this->module

            ]
        );
        $this->fillFromRequest($request);

        $this->set('breadcrumbs', $this->breadcrumbs());
    }


    private function breadcrumbs(): array
    {
        /** @var Shop $shop */
        $shop = $this->get('shop');


        return array_merge(
            (new ShopIndex())->getBreadcrumbs($this->module.'s'),
            [
                'shop' => [
                    'route'           => Str::plural($this->module).'.index',
                    'routeParameters' => $shop->id,
                    'name'            => $shop->code,
                    'current'         => false
                ],
            ]
        );
    }


}
