<?php
/** @noinspection PhpUnusedParameterInspection */

/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 07 Mar 2022 18:20:21 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\HumanResources;

use App\Actions\HumanResources\WorkTarget\IndexWorkTargetInEmployee;
use App\Actions\HumanResources\WorkTarget\IndexWorkTargetInTenant;
use App\Actions\HumanResources\WorkTarget\IndexWorkTargetInTenantWithInterval;
use App\Enums\Interval;
use App\Http\Controllers\Controller;
use App\Models\HumanResources\Employee;
use App\Models\HumanResources\WorkTarget;
use Illuminate\Http\Request;
use Inertia\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class WorkTargetsController extends Controller
{
    public function indexInTenant(): Response
    {
        return IndexWorkTargetInTenant::make()->asInertia();
    }

    public function indexInTenantWithInterval(Interval $interval): Response
    {
        return IndexWorkTargetInTenantWithInterval::make()->asInertiaWithInterval($interval);
    }



    public function indexInEmployee(Employee $Employee): Response
    {
        return IndexWorkTargetInEmployee::make()->asInertia($Employee);
    }

    public function showInTenant(WorkTarget $workTarget): Response
    {
        return ShowWorkTarget::make()->asInertia(parent: 'tenant', workTarget: $workTarget);
    }

    public function showInEmployee(Employee $Employee,WorkTarget $workTarget): Response
    {
        return ShowWorkTarget::make()->asInertia(parent: 'employee', workTarget: $workTarget);
    }

    public function editInTenant(WorkTarget $workTarget): Response
    {
        return ShowEditWorkTarget::make()->asInertia(parent: 'tenant', workTarget: $workTarget);
    }

    public function editInEmployee(Employee $Employee,WorkTarget $workTarget): Response
    {
        return ShowEditWorkTarget::make()->asInertia(parent: 'employee', workTarget: $workTarget);
    }


    public function update(WorkTarget $workTarget, Request $request): RedirectResponse
    {
        return UpdateWorkTarget::make()->asInertia($workTarget, $request);
    }
}
