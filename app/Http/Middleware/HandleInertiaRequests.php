<?php

namespace App\Http\Middleware;

use App\Http\Controllers\UI\ModuleController;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

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

            'appType' => app('currentTenant')->businessType->slug,
            'modules' => function () use ($request) {
                if (! $request->user()) {
                    return [];
                }

                return (new ModuleController())();



            },
        ]);
    }
}
