<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 27 Jan 2022 19:18:29 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\System;

use App\Actions\System\Guest\IndexGuest;
use App\Actions\System\Guest\ShowEditGuest;
use App\Actions\System\Guest\ShowGuest;
use App\Actions\System\Guest\UpdateGuest;
use App\Http\Controllers\Controller;
use App\Models\HumanResources\Guest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;


class GuestController extends Controller
{

    public function index(): Response
    {
        return IndexGuest::make()->asInertia();
    }


    public function show(Guest $guest): Response
    {
        return ShowGuest::make()->asInertia($guest);
    }

    public function edit(Guest $guest): Response
    {
        return ShowEditGuest::make()->asInertia($guest);
    }

    public function update(Guest $guest, Request $request): RedirectResponse
    {
        return UpdateGuest::make()->asInertia($guest, $request);
    }

}

