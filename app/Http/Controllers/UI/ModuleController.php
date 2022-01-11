<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 20 Aug 2021 20:18:26 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\UI;

use App\Http\Controllers\Controller;
use App\Models\System\User;
use App\Models\Trade\Shop;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;


class ModuleController extends Controller
{


    public function __invoke(?User $user): array
    {
        if (!$user) {
            return [];
        }

        $layout = [];




        foreach (config('business_types.'.app('currentTenant')->businessType->slug.'.modules', []) as $moduleKey => $module) {
            if ($moduleKey == 'shops') {
                $shops = Shop::withTrashed()->where('type', 'b2b')->get()->filter(function ($shop, $key) use ($user) {
                    return $user->hasPermissionTo("shops.$shop->id.view");
                });


                if ($shops->count()) {
                    $options  = [];

                    $sections = [];
                    foreach (Arr::get($module, 'sections', []) as $sectionRoute => $section) {
                        $sectionPermissions = $section['permissions'] ?? false;

                        if (!$sectionPermissions or $user->hasAnyPermission($sectionPermissions)) {
                            $sections[$sectionRoute] = [
                                'icon' => Arr::get($section, 'fa', ['fal', 'angle-right']),
                                'name' => Arr::get($section, 'name'),
                            ];
                        }
                    }


                    foreach($shops as $shop){
                        $options[$shop->id]=[
                            'name'=>$shop->code
                        ];
                    }



                    $layout[$moduleKey] = [
                        'icon'     => Arr::get($module, 'fa', ['fal', 'angle-right']),
                        'name'     => trans_choice('modules.'.Arr::get($module, 'name'),$shops->count()),
                        'route'    => Arr::get($module, 'route'),
                        'sections' => $sections,
                        'options'  => $options,
                        'current_option'=>null
                    ];
                }
            } else {
                $modulePermissions = $module['permissions'] ?? false;
                if ($modulePermissions and $user->hasAnyPermission($modulePermissions)) {
                    $sections = [];
                    foreach (Arr::get($module, 'sections', []) as $sectionRoute => $section) {
                        $sectionPermissions = $section['permissions'] ?? false;

                        if (!$sectionPermissions or $user->hasAnyPermission($sectionPermissions)) {
                            $sections[$sectionRoute] = [
                                'icon' => Arr::get($section, 'fa', ['fal', 'angle-right']),
                                'name' => Arr::get($section, 'name'),
                            ];
                        }
                    }


                    $layout[$moduleKey] = [
                        'icon'     => Arr::get($module, 'fa', ['fal', 'angle-right']),
                        'name'     => Arr::get($module, 'name'),
                        'route'    => Arr::get($module, 'route'),
                        'sections' => $sections
                    ];
                }
            }
        }


        return $layout;
    }


}


