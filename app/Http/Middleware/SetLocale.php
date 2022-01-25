<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 25 Jan 2022 18:01:32 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{

    public function handle(Request $request, Closure $next)
    {
        if ($request->user() and $request->user()->userable_type!='Tenant') {
            App::setLocale($request->user()->language->code);
        } else {

            App::setLocale(App('currentTenant')->language->code);
        }

        return $next($request);
    }
}
