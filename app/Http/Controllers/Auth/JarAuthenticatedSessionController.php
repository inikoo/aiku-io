<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 28 Feb 2022 22:15:36 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Auth;


use App\Http\Requests\Auth\ProxyLoginRequest;
use App\Http\Requests\SetTenantPreLoginRequest;
use App\Models\Auth\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\ValidationException;


class JarAuthenticatedSessionController extends AuthenticatedSessionController
{


    public function proxyStore(ProxyLoginRequest $request): RedirectResponse
    {
        return $this->processStore($request);
    }





    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function setTenant(SetTenantPreLoginRequest $request): RedirectResponse
    {
        $validated = $request->validated();


        $user = (new User())->where('jar_username', $validated['username'])->first();


        if ($user) {
            Cookie::forget('tenant');
            Cookie::queue(Cookie::forever('tenant', encrypt($user->tenant_id)));
            session(['tenant' => $user->tenant_id]);


            return redirect()->action(
                [JarAuthenticatedSessionController::class, 'proxyStore'], ['token' => encrypt(json_encode($validated))]
            );


        }

        throw ValidationException::withMessages([
                                                    'username' => __('auth.failed'),
                                                ]);
    }


    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function proxyAuthenticate($request, $validated)
    {
        $subdomain = current(explode('.', $request->getHost()));

        $credentials = match ($subdomain) {
            'agents' => [
                'status'       => true,
                'jar_username' => $validated['username'],
                'password'     => $validated['password'],
            ],
            default => array_merge(

                [
                    'username'  => $validated['username'],
                    'password'  => $validated['password'],
                    'status'    => true,
                    'tenant_id' => App('currentTenant')->id
                ]
            ),
        };


        if (!Auth::attempt(
            $credentials,
            $validated['remember'],
        )) {

            throw ValidationException::withMessages([
                                                        'username' => __('auth.failed'),
                                                    ]);
        }
    }


}
