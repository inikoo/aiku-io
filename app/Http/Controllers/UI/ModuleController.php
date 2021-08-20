<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 20 Aug 2021 20:18:26 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\UI;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class ModuleController extends Controller
{

    public function __invoke(): array
    {
        $layout = [];
        if (Auth::user()) {
            $layout = config('modules.'.app('currentTenant')->type, []);
        }

        $translated = collect($layout)->toLocale('es');
        $translated = $translated->map(function ($item) {
            $sections=collect($item['sections']);
            $item['sections']=$sections->toLocale('es')->all();
            return $item ;
        });

        return $translated->all();
    }



}


