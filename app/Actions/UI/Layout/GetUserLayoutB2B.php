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
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\Concerns\AsAction;

class GetUserLayoutB2B extends GetUserLayout
{
    use AsAction;


    private array $models;

    private Collection $shops;
    private Collection $fulfilment_houses;
    private Collection $websites;
    private Collection $warehouses;
    private Collection $workshops;

    protected function initialize($user)
    {
        $this->user   = $user;
        $this->models = [];

        $this->shops = Shop::withTrashed()->where('type', 'shop')->get();

        $this->models['shop']             = $this->shops->filter(function ($shop) use ($user) {
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

    #[Pure] protected function canShow($moduleKey): bool
    {
        return match ($moduleKey) {
            'shops' => $this->shops->count() > 1 and $this->models['shop']->count(),
            'shop' => $this->models['shop']->count() > 0,
            'fulfilment_houses' => $this->fulfilment_houses->count() > 1 and $this->models['fulfilment_house']->count(),
            'fulfilment_house' => $this->models['fulfilment_house']->count() > 0,

            'websites' => $this->websites->count() > 1 and $this->models['website']->count(),
            'website' => $this->models['website']->count() > 0,

            'warehouses' => $this->warehouses->count() > 1 and $this->models['warehouse']->count(),
            'warehouse' => $this->warehouses->count() > 1,

            'workshops' => $this->workshops->count() and $this->models['workshop']->count(),
            'workshop' => $this->models['workshop']->count() > 0,

            default => true,
        };
    }

    protected function prepareModule($module)
    {
        return match ($module['id']) {
            'shop',
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
                } else {
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
