<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 17 Sep 2021 20:37:01 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Middleware;

use App\Actions\UI\Localisation\GetUITranslations;
use App\Models\Account\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
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

        $hasTenant=Tenant::checkCurrent();


        $firstLoadOnlyProps = (!$request->inertia() or Session::get('redirectFromLogin')) ? [

            'tenant'  => $hasTenant?app('currentTenant')->only('name', 'nickname'):[],
            'appType' => $hasTenant?app('currentTenant')->tenantType->code:null,
            'modules' => function () use ($request,$hasTenant) {
                if($hasTenant) {
                    /** @var \App\Models\Account\TenantType $tenantType */
                    $tenantType = app('currentTenant')->tenantType;

                    return $tenantType->getUserLayout($request->user());
                }else{
                    return [];
                }
            },


            'language'     => App::currentLocale(),
            'translations' => fn() => GetUITranslations::run()
        ] : [];

        Session::forget('redirectFromLogin');

        return array_merge(parent::share($request), $firstLoadOnlyProps, [

            'currentModels' => fn() => [
                'shop'      => session('currentShop'),
                'warehouse' => session('currentWarehouse'),
                'website'   => session('currentWebsite')
            ],
            'auth.user'     => fn() => $request->user()
                ? $request->user()->only('id', 'name', 'email', 'userable_type')
                : null,


        ]);
    }
}
