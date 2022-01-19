<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 24 Aug 2021 03:12:21 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\System;

use App\Actions\Account\Account\ShowAccount;
use App\Actions\Account\Account\ShowEditAccount;
use App\Actions\Account\Account\UpdateAccount;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Inertia\Response;

class AccountController extends Controller
{


    public function show(): Response
    {
        return ShowAccount::make()->asInertia();
    }

    public function edit(): Response
    {
        return ShowEditAccount::make()->asInertia();
    }

    public function update(Request $request): RedirectResponse
    {
        return UpdateAccount::make()->asInertia($request);
    }


}

