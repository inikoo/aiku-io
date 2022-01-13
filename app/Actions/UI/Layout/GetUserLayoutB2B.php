<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 12 Jan 2022 00:15:45 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\UI\Layout;

use App\Models\Trade\Shop;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\Concerns\AsAction;

class GetUserLayoutB2B extends GetUserLayout
{
    use AsAction;


    private Collection $shops;
    private Collection $fulfilmentHouses;



    protected function initialize($user)
    {
        $this->user = $user;

        $this->shops         = Shop::withTrashed()->where('type', 'shop')->get()->filter(function ($shop) use ($user) {
            return $user->hasPermissionTo("shops.$shop->id.view");
        });
        $this->fulfilmentHouses = Shop::withTrashed()->where('type', 'fulfilment_house')->get()->filter(function ($shop) use ($user) {
            return $user->hasPermissionTo("shops.$shop->id.view");
        });


    }

    #[Pure] protected function canShow($moduleKey): bool
    {
        return match ($moduleKey) {
            'shops' => $this->shops->count() > 1,
            'shop' => $this->shops->count() != 0,
            'fulfilment_houses' => $this->fulfilmentHouses->count() > 1,
            'fulfilment_house' => $this->fulfilmentHouses->count() != 0,
            default => true,
        };
    }

    protected function prepareModule($module)
    {
        return match ($module['id']) {
            'shop' => (function () use ($module) {
                $options = [];
                foreach ($this->shops as $shop) {
                    $options[$shop->id] = [
                        'icon' => null,
                        'code' => $shop->code,
                        'name' => $shop->name
                    ];
                }

                $module['options'] = $options;


                return $module;
            })(),
            'fulfilment_house' => (function () use ($module) {
                $options = [];
                foreach ($this->fulfilmentHouses as $shop) {
                    $options[$shop->id] = [
                        'icon' => null,
                        'code' => $shop->code,
                        'name' => $shop->name
                    ];
                }

                $module['options'] = $options;


                return $module;
            })(),

            default => $module,
        };
    }

}
