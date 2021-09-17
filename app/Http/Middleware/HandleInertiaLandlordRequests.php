<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 17 Sep 2021 20:36:55 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Middleware;

use App\Http\Controllers\UI\ModuleController;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaLandlordRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'landlord';

    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
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
     * @return array
     */
    public function share(Request $request): array
    {



        return array_merge(parent::share($request), [

            'auth.user' => fn () => $request->user()
                ? $request->user()->only('id', 'name', 'email')
                : null,

            'modules' => function () use ($request) {
                if (! $request->user()) {
                    return [];
                }

                return (new ModuleController())();



            },
        ]);
    }
}
