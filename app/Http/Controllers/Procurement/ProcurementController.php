<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 15 Jan 2022 22:28:04 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Procurement;


use App\Http\Controllers\Controller;
use App\Http\Controllers\Procurement\IndexWarehouse;
use App\Http\Controllers\Procurement\ShowWarehouse;
use App\Http\Controllers\Procurement\Warehouse;
use Inertia\Response;


class ProcurementController extends Controller
{


    public function dashboard(): Response
    {
        return DashboardProcurement::make()->asInertia();
    }



}
