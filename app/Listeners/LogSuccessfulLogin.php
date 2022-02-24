<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

use Illuminate\Support\Facades\Cookie;

class LogSuccessfulLogin
{

    public function __construct()
    {
        //
    }

    public function handle(Login $event)
    {
        /** @var \App\Models\Auth\User $authUser */
        $authUser = $event->user;

        Cookie::forget('tenant');
        Cookie::queue(Cookie::forever('tenant', encrypt($authUser->tenant_id)));
    }
}
