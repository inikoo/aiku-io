<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 15 Jan 2022 01:59:06 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\System;

use App\Actions\System\User\IndexUser;
use App\Actions\System\User\ShowEditUser;
use App\Actions\System\User\ShowUser;
use App\Actions\System\User\UpdateUser;
use App\Http\Controllers\Controller;

use App\Models\Auth\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Illuminate\Http\Request;


class UserController extends Controller
{

    public function index(): Response
    {
        return IndexUser::make()->asInertia();
    }

    public function show(User $user): Response
    {
        return ShowUser::make()->asInertia($user);
    }

    public function edit(User $user): Response
    {
        return ShowEditUser::make()->asInertia($user);
    }

    public function update(User $user, Request $request): RedirectResponse
    {
        return UpdateUser::make()->asInertia($user, $request);
    }

}

