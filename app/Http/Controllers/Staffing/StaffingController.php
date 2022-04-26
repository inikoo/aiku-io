<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 06 Apr 2022 18:07:06 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Staffing;

use App\Actions\Staffing\ShowStaffingDashboard;
use App\Http\Controllers\Controller;
use Inertia\Response;


class StaffingController extends Controller
{
    public function dashboard(): Response
    {
        return ShowStaffingDashboard::make()->asInertia();
    }

}
