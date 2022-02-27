<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Account\Tenant;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     **/
    public function create(): Response
    {
        return Inertia::render('login', [
            'labels'     => [
                'username'      => __('Username'),
                'password'      => __('Password'),
                'login'         => __('Log in'),
                'remember_me'   => __('Remember me'),
                'generic_error' => __('Whoops! Something went wrong.')
            ],
            'tenantCode' => Tenant::checkCurrent() &&  app('currentTenant')->domain!='' ? app('currentTenant')->code : null,
            'status'     => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(LoginRequest $request, $guard = 'ecommerce'): RedirectResponse
    {
        $request->authenticate($guard);

        $request->session()->regenerate();



        Session::put('redirectFromLogin', '1');

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     */
    public function destroy(Request $request, $guard = 'web'): RedirectResponse
    {
        Auth::guard($guard)->logout();

        Cookie::queue(Cookie::forget('tenant'));

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
