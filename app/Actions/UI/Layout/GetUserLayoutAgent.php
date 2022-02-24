<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 24 Feb 2022 20:19:48 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\UI\Layout;

use App\Models\Inventory\Warehouse;
use App\Models\Marketing\Shop;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

/**
 * @property array $modelsCount
 * @property array $visibleModels
 */
class GetUserLayoutAgent extends GetUserLayout
{
    use AsAction;





    protected function initialize($user)
    {
        $this->user = $user;

        $this->modelsCount = [
            'marketing' => Shop::count(),
            'inventory' => Warehouse::count()
        ];

        $this->visibleModels = [
            'marketing' => Shop::get()->filter(function ($shop) use ($user) {
                return $user->hasPermissionTo("shops.view.$shop->id");
            })->map(function ($shop) {
                return Arr::only($shop->toArray(), ['id', 'code', 'name']);
            })->mapWithKeys(function ($item) {
                return [$item['id'] => Arr::except($item, 'id')];
            })->all(),
            'inventory' =>Warehouse::get()->filter(function ($warehouse) use ($user) {
                return $user->hasPermissionTo("warehouses.view.$warehouse->id");
            })->map(function ($warehouse) {
                return Arr::only($warehouse->toArray(), ['id', 'code', 'name']);
            })->mapWithKeys(function ($item) {
                return [$item['id'] => Arr::except($item, 'id')];
            })->all()
        ];

        session(['marketingCount' =>$this->modelsCount['marketing']]);

        if ($this->modelsCount['marketing'] == 1) {

            session(['currentShop' =>array_key_first($this->visibleModels['marketing'])]);
        }
    }

    protected function getSections($module)
    {
        if ($module['code'] == 'marketing') {
            return [


                'marketing.dashboard'       => [
                    'metaSection' => 'shops',
                    'name'        => __('Marketing'),
                    'shortName'   => __('Marketing'),
                    'icon'        => ['fal', 'cash-register'],
                ],
                'marketing.shops.index'     => [
                    'metaSection' => 'shops',
                    'name'        => __('Shops'),
                    'shortName'   => __('Shops'),
                    'icon'        => ['fal', 'bars'],
                ],
                'marketing.customers.index' => [
                    'metaSection' => 'shops',
                    'name'        => __('Customers'),
                    'icon'        => ['fal', 'layer-group'],
                ],
                'marketing.orders.index'    => [
                    'metaSection' => 'shops',
                    'name'        => __('Orders'),
                    'icon'        => ['fal', 'layer-group'],
                ],


                'marketing.shops.show.customers.index' => [
                    'metaSection' => 'shop',
                    'name'        => __('Customers'),
                    'icon'        => ['fal', 'user'],
                ],
                'marketing.shops.show.orders.index'    => [
                    'metaSection' => 'shop',
                    'name'        => __('Orders'),
                    'icon'        => ['fal', 'shopping-cart'],
                ],

            ];
        }

        return Arr::get($module, 'sections', []);
    }


    protected function getModelsCount($module): int
    {
        return $this->modelsCount[$module]??0;

    }

    protected function getVisibleModels($user, $module): ?array
    {
        return match ($module['code']) {
            'marketing',
            => (function () use ($user) {
                return Shop::get()->filter(function ($shop) use ($user) {
                    return $user->hasPermissionTo("shops.view.$shop->id");
                })->map(function ($shop) {
                    return Arr::only($shop->toArray(), ['id', 'code', 'name']);
                })->mapWithKeys(function ($item) {
                    return [$item['id'] => Arr::except($item, 'id')];
                })->all();
            })(),
            'inventory',
            => (function () use ($user) {
                return Warehouse::get()->filter(function ($warehouse) use ($user) {
                    return $user->hasPermissionTo("warehouses.view.$warehouse->id");
                })->map(function ($warehouse) {
                    return Arr::only($warehouse->toArray(), ['id', 'code', 'name']);
                })->mapWithKeys(function ($item) {
                    return [$item['id'] => Arr::except($item, 'id')];
                })->all();
            })(),
            default => null,
        };
    }


}
