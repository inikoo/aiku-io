<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 17 Sep 2021 20:37:01 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaTenantsRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'tenants';

    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [

            'auth.user' => fn() => $request->user()
                ? $request->user()->only('id', 'name', 'email')
                : null,
            'tenant'    => app('currentTenant')->name,
            'appType'   => app('currentTenant')->businessType->slug,
            'modules'   => function () use ($request) {

                /** @var \App\Models\Account\BusinessType $businessType */
                $businessType=app('currentTenant')->businessType;

                return  $businessType->getUserLayout($request->user());
            },
        ]);
    }
}
