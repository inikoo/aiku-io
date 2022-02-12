<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 08 Feb 2022 06:17:19 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Reports;


use App\Actions\Reports\ShowReportsDashboard;
use App\Http\Controllers\Controller;
use Inertia\Response;


class ReportsController extends Controller
{


    public function dashboard(): Response
    {
        return ShowReportsDashboard::make()->asInertia();
    }



}
