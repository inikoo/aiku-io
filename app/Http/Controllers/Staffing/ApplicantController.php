<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 06 Apr 2022 18:39:30 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Staffing;



use App\Actions\Staffing\Applicant\IndexApplicantInTenant;
use App\Http\Controllers\Controller;
use App\Models\Staffing\Applicant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;


class ApplicantController extends Controller
{


    public function index(): Response
    {
        return IndexApplicantInTenant::make()->asInertia();
    }

    public function show(Applicant $applicant): Response
    {
        return ShowApplicant::make()->asInertia($applicant);
    }

    public function edit(Applicant $applicant): Response
    {
        return ShowEditApplicant::make()->asInertia($applicant);
    }

    public function update(Applicant $applicant, Request $request): RedirectResponse
    {
        return UpdateApplicant::make()->asInertia($applicant, $request);
    }


}
