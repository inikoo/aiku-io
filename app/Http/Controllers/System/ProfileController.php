<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 22 Jan 2022 15:27:53 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\System;

use App\Actions\Auth\Role\IndexUserRole;
use App\Actions\System\Profile\ShowEditProfile;
use App\Actions\System\Profile\ShowProfile;
use App\Actions\System\Profile\UpdateProfile;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class ProfileController extends Controller
{


    public function show(Request $request): Response
    {
        return ShowProfile::make()->asInertia($request);
    }

    public function edit(Request $request): Response
    {
        return ShowEditProfile::make()->asInertia($request);
    }

    public function update(Request $request): RedirectResponse
    {
        return UpdateProfile::make()->asInertia($request);
    }

    public function roles(Request $request): Response
    {
        return IndexUserRole::make()->asInertia($request->user(),$request);
    }


}

