<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 22 Jan 2022 17:56:38 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\System;


use App\Actions\Auth\Role\IndexRole;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class RoleController extends Controller
{

    public function index(Request $request): Response
    {
        return IndexRole::make()->asInertia($request);
    }

    public function show(Request $request): Response
    {
        return ShowRole::make()->asInertia($request);
    }

    public function edit(Request $request): Response
    {
        return ShowEditRole::make()->asInertia($request);
    }

    public function update(Request $request): RedirectResponse
    {
        return UpdateRole::make()->asInertia($request);
    }


}

