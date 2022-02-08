<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 12 Jan 2022 00:15:45 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\UI\Layout;

use App\Models\Inventory\Warehouse;
use App\Models\Production\Workshop;
use App\Models\Trade\Shop;
use App\Models\Web\Website;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\Concerns\AsAction;

class GetUserLayoutEcommerce extends GetUserLayout
{
    use AsAction;


    private array $models;

    private Collection $ecommerce_shops;
    private Collection $fulfilment_houses;
    private Collection $websites;
    private Collection $warehouses;
    private Collection $workshops;

    protected function initialize($user)
    {
        $this->user   = $user;
        $this->models = [];

        $this->ecommerce_shops = Shop::withTrashed()->where('type', 'shop')->get();

        $this->models['ecommerce_shop']   = $this->ecommerce_shops->filter(function ($shop) use ($user) {
            return $user->hasPermissionTo("shops.$shop->id.view");
        });
        $this->fulfilment_houses          = Shop::withTrashed()->where('type', 'fulfilment_house')->get();
        $this->models['fulfilment_house'] = $this->fulfilment_houses->filter(function ($shop) use ($user) {
            return $user->hasPermissionTo("shops.$shop->id.view");
        });
        $this->websites                   = Website::withTrashed()->get();
        $this->models['website']          = $this->websites->filter(function ($website) use ($user) {
            return $user->hasPermissionTo("websites.$website->id.view");
        });
        $this->warehouses                 = Warehouse::withTrashed()->get();
        $this->models['warehouse']        = $this->warehouses->filter(function ($warehouse) use ($user) {
            return $user->hasPermissionTo("warehouses.$warehouse->id.view");
        });
        $this->workshops                  = Workshop::withTrashed()->get();
        $this->models['workshop']         = $this->workshops->filter(function ($workshop) use ($user) {
            return $user->hasPermissionTo("websites.$workshop->id.view");
        });
    }


    protected function getSections($module)
    {
        if ($module['code'] == 'shops') {


            return [


                'shops.dashboard'       => [
                    'metaSection' => 'shops',
                    'name'  => __('Shops Dashboard'),
                    'shortName'  => __('Dashboard'),
                    'icon'  => ['fal', 'tachometer-alt-fast'],
                ],
                'shops.index'           => [
                    'metaSection' => 'shops',
                    'name'  => __('Shops Index'),
                    'shortName'  => __('Shops'),
                    'icon'  => ['fal', 'bars'],
                ],
                'shops.customers.index' => [
                    'metaSection' => 'shops',
                    'name'  => __('Customers'),
                    'icon'  => ['fal', 'layer-group'],
                ],
                'shops.orders.index'    => [
                    'metaSection' => 'shops',
                    'name'  => __('Orders'),
                    'icon'  => ['fal', 'layer-group'],
                ],


                'shops.show.customers.index' => [
                    'metaSection' => 'shop',
                    'name'  => __('Customers'),
                    'icon'  => ['fal', 'user'],
                ],
                'shops.show.orders.index'    => [
                    'metaSection' => 'shop',
                    'name'  => __('Orders'),
                    'icon'  => ['fal', 'shopping-cart'],
                ],

            ];
        }

        return Arr::get($module, 'sections', []);
    }


    #[Pure] protected function canShow($moduleKey): bool
    {
        return match ($moduleKey) {
            'ecommerce_shops' => $this->ecommerce_shops->count() > 1 and $this->models['ecommerce_shop']->count(),
            'ecommerce_shop' => $this->models['ecommerce_shop']->count() > 0,
            'fulfilment_houses' => $this->fulfilment_houses->count() > 1 and $this->models['fulfilment_house']->count(),
            'fulfilment_house' => $this->models['fulfilment_house']->count() > 0,

            'websites' => $this->websites->count() > 1 and $this->models['website']->count(),
            'website' => $this->models['website']->count() > 0,

            'warehouses' => $this->warehouses->count() > 1 and $this->models['warehouse']->count(),
            'warehouse' => $this->models['warehouse']->count() > 0,

            'workshops' => $this->workshops->count() and $this->models['workshop']->count(),
            'workshop' => $this->models['workshop']->count() > 0,

            default => true,
        };
    }

    protected function prepareModule($module)
    {
        return match ($module['id']) {
            'ecommerce_shop',
            'fulfilment_house',
            'website',
            'warehouse',
            'workshop',
            => (function () use ($module) {
                $options = [];
                foreach ($this->models[$module['id']] as $model) {
                    $options[$model->id] = [
                        'icon' => null,
                        'code' => $model->code,
                        'name' => $model->name
                    ];
                }


                $module['options']    = $options;
                $module['route']      = Str::plural($module['id']).'.show';
                $module['indexRoute'] = Str::plural($module['id']).'.index';


                if ($this->models[$module['id']]->count() == 1) {
                    $module['type']            = 'standard';
                    $module['routeParameters'] = $this->models[$module['id']][0]->id;
                } elseif ($this->models[$module['id']]->count() > 1) {
                    $module['fallbackModel'] = $this->models[$module['id']][0]->id;
                }


                return $module;
            })(),
            'inventory' => (function () use ($module) {
                if ($this->models['warehouse']->count() != 1) {
                    unset($module['sections']['inventory.warehouses.show.locations.index']);
                    unset($module['sections']['inventory.warehouses.show.areas.index']);
                }


                return $module;
            })(),
            default => $module,
        };
    }

}
