<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 12 Jan 2022 00:15:45 Malaysia Time, Kuala Lumpur, Malaysia
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
class GetUserLayoutEcommerce extends GetUserLayout
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
            'inventory' => Warehouse::get()->filter(function ($warehouse) use ($user) {
                return $user->hasPermissionTo("warehouses.view.$warehouse->id");
            })->map(function ($warehouse) {
                return Arr::only($warehouse->toArray(), ['id', 'code', 'name']);
            })->mapWithKeys(function ($item) {
                return [$item['id'] => Arr::except($item, 'id')];
            })->all()
        ];

        session(['marketingCount' => $this->modelsCount['marketing']]);
        if ($this->modelsCount['marketing'] == 1) {
            session(['currentShop' => array_key_first($this->visibleModels['marketing'])]);
        }
        session(['inventoryCount' => $this->modelsCount['inventory']]);
        if ($this->modelsCount['inventory'] == 1) {
            session(['currentWarehouse' => array_key_first($this->visibleModels['inventory'])]);
        }
    }

    protected function getSections($module)
    {
        switch ($module['code']) {
            case 'marketing':
                return [

                    'marketing-model' => [
                        'model' => 'shop',
                        'indexLabel'=>__('Shops')

                    ],

                    'marketing.dashboard'       => [
                        'metaSection' => 'shops',
                        'name'        => __('Marketing'),
                        'shortName'   => __('Marketing'),
                        'icon'        => ['fal', 'cash-register'],
                    ],
                    /*
                    'marketing.shops.index'     => [
                        'metaSection' => 'shops',
                        'name'        => __('Shops'),
                        'shortName'   => __('Shops'),
                        'icon'        => ['fal', 'bars'],
                    ],
                    */
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
            case 'inventory':
                $sections = Arr::get($module, 'sections', []);


                if (app('currentTenant')->stats->has_fulfilment) {
                    $sections['inventory.unique_stocks.index'] = [
                        'name' => 'Stored goods',
                        'icon' => ['fal', 'pallet'],
                    ];

                    $sections['inventory.fulfilment_stocks.index'] = [
                        'name' => 'Fulfilment stock',
                        'icon' => ['fal', 'pallet'],
                    ];
                }

                $sections['inventory-model'] = [
                    'model' => 'warehouse',
                    'indexLabel'=>__('Warehouses')
                ];

                $sections['inventory.warehouses.show.areas.index'] = [
                    'metaSection' => 'warehouse',
                    'name'        => __('Areas'),
                    'icon'        => ['fal', 'draw-square'],
                ];
                $sections['inventory.warehouses.show.locations.index'] = [
                    'metaSection' => 'warehouse',
                    'name'        => __('Locations'),
                    'icon'        => ['fal', 'inventory'],
                ];


                /*

                $sections['inventory.warehouses.index'] = [
                    'metaSection' => 'warehouses',
                    'name'        => __('Warehouses'),
                    'icon'        => ['fal', 'bars'],
                ];
                */

                return $sections;

            default:
                return Arr::get($module, 'sections', []);
        }
    }


    protected function getModelsCount($module): int
    {
        return $this->modelsCount[$module] ?? 0;
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
