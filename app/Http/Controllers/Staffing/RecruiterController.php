<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 07 Apr 2022 15:34:03 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Staffing;



use App\Actions\Staffing\Recruiter\IndexRecruiter;
use App\Http\Controllers\Controller;
use App\Models\Staffing\Recruiter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;


class RecruiterController extends Controller
{


    public function index(): Response
    {
        return IndexRecruiter::make()->asInertia();
    }

    public function show(Recruiter $recruiter): Response
    {
        return ShowRecruiter::make()->asInertia($recruiter);
    }

    public function edit(Recruiter $recruiter): Response
    {
        return ShowEditRecruiter::make()->asInertia($recruiter);
    }

    public function update(Recruiter $recruiter, Request $request): RedirectResponse
    {
        return UpdateRecruiter::make()->asInertia($recruiter, $request);
    }


}
