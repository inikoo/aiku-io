<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 28 Jan 2022 16:35:44 Malaysia Time, Kuala Lumpur, Malaysia
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


/**
 * @property Shop $shop
 * @property string $module
 * @property string $type
 */
class ShowEcommerceShop
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


    public function asInertia(Shop $shop,  array $attributes = []): Response
    {
        $this->set('shop', $shop)->fill($attributes);
        $this->set('type', 'shop');
        $this->validateAttributes();


        session(['currentEcommerceShop' => $shop->id]);


        return Inertia::render(
           'Common/ShowModel',
            [
                'headerData' => [
                    'module'      => $this->module,
                    'title'       => $shop->name,
                    'breadcrumbs' => $this->get('breadcrumbs'),

                ],
                'model'       => $shop
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {

        $this->fillFromRequest($request);

        $this->set('breadcrumbs', $this->breadcrumbs());
    }


    private function breadcrumbs(): array
    {
        /** @var Shop $shop */
        $shop = $this->get('shop');


        return array_merge(
            (new IndexEcommerceShop())->getBreadcrumbs(),
            [
                'shop' => [
                    'route'           => 'ecommerce_shops.show',
                    'routeParameters' => $shop->id,
                    'name'            => $shop->code,
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
