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
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\Concerns\AsAction;

class GetUserLayoutB2B extends GetUserLayout
{
    use AsAction;


    private array $models;

    protected function initialize($user)
    {
        $this->user   = $user;
        $this->models = [];

        $this->models['shop']             = Shop::withTrashed()->where('type', 'shop')->get()->filter(function ($shop) use ($user) {
            return $user->hasPermissionTo("shops.$shop->id.view");
        });
        $this->models['fulfilment_house'] = Shop::withTrashed()->where('type', 'fulfilment_house')->get()->filter(function ($shop) use ($user) {
            return $user->hasPermissionTo("shops.$shop->id.view");
        });
        $this->models['website']          = Website::withTrashed()->get()->filter(function ($website) use ($user) {
            return $user->hasPermissionTo("websites.$website->id.view");
        });
        $this->models['warehouse']        = Warehouse::withTrashed()->get()->filter(function ($warehouse) use ($user) {
            return $user->hasPermissionTo("warehouses.$warehouse->id.view");
        });
        $this->models['workshop']         = Workshop::withTrashed()->get()->filter(function ($workshop) use ($user) {
            return $user->hasPermissionTo("websites.$workshop->id.view");
        });
    }

    #[Pure] protected function canShow($moduleKey): bool
    {
        return match ($moduleKey) {
            'shops' => $this->models['shop']->count() > 1,
            'shop' => $this->models['shop']->count() > 0,
            'fulfilment_houses' => $this->models['fulfilment_house']->count() > 1,
            'fulfilment_house' => $this->models['fulfilment_house']->count() > 0,
            'websites' => $this->models['website']->count() > 1,
            'website' => $this->models['website']->count() > 0,
            'warehouses' => $this->models['warehouse']->count() > 1,
            'warehouse' => $this->models['warehouse']->count() > 0,
            'workshops' => $this->models['workshop']->count() > 1,
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

                $module['options'] = $options;
                $module['route'] = Str::plural($module['id']).'.show';
                $module['indexRoute'] = Str::plural($module['id']).'.index';


                if ($this->models[$module['id']]->count() == 1) {
                    $module['type']            = 'standard';
                    $module['routeParameters'] = $this->models[$module['id']][0]->id;
                }


                return $module;
            })(),

            default => $module,
        };
    }

}
