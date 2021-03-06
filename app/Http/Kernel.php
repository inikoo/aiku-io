<?php

namespace App\Http;

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\ForceJsonResponse;
use App\Http\Middleware\HandleInertiaLandlordRequests;
use App\Http\Middleware\HandleInertiaTenantsRequests;
use App\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\SetPermissionTeam;
use App\Http\Middleware\TrimStrings;
use App\Http\Middleware\TrustHosts;
use App\Http\Middleware\TrustProxies;
use App\Http\Middleware\VerifyCsrfToken;
use Fruitcake\Cors\HandleCors;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Spatie\Multitenancy\Http\Middleware\EnsureValidTenantSession;
use Spatie\Multitenancy\Http\Middleware\NeedsTenant;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * This middleware is run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        TrustHosts::class,
        TrustProxies::class,
        HandleCors::class,
        PreventRequestsDuringMaintenance::class,
        ValidatePostSize::class,
        TrimStrings::class,
        ConvertEmptyStringsToNull::class,
    ];


    protected $middlewareGroups = [

        'app_with_subdomain' => [
            NeedsTenant::class,
            SetPermissionTeam::class,
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            EnsureValidTenantSession::class,
            SetLocale::class,
            HandleInertiaTenantsRequests::class,

        ],

        'app_jar' => [

            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            SetLocale::class,
            HandleInertiaTenantsRequests::class,


        ],



        'api' => [
            ForceJsonResponse::class,
            NeedsTenant::class,
            EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            SubstituteBindings::class,
            'auth:api'
        ],


        'landlord' => [

            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            HandleInertiaLandlordRequests::class,
        ],


        'landlord_api' => [
            ForceJsonResponse::class,
            EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            SubstituteBindings::class,
            'auth:landlord_api'
        ],


    ];

    /**
     * The application's route middleware.
     *
     * This middleware may be assigned to group or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'json.response'            => ForceJsonResponse::class,
        'auth'                     => Authenticate::class,
        'auth.basic'               => AuthenticateWithBasicAuth::class,
        'cache.headers'            => SetCacheHeaders::class,
        'can'                      => Authorize::class,
        'guest'                    => RedirectIfAuthenticated::class,
        'password.confirm'         => RequirePassword::class,
        'signed'                   => ValidateSignature::class,
        'throttle'                 => ThrottleRequests::class,
        'verified'                 => EnsureEmailIsVerified::class,
        'multitenancy.require'     => NeedsTenant::class,
        'multitenancy.firewall'    => EnsureValidTenantSession::class,
        'multitenancy.permissions' => SetPermissionTeam::class
    ];


}
