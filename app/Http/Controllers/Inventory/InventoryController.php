<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 20 Jan 2022 16:42:39 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Inventory;


use App\Actions\Inventory\ShowInventoryDashboard;
use App\Http\Controllers\Controller;
use Inertia\Response;


class InventoryController extends Controller
{


    public function dashboard(): Response
    {
        return ShowInventoryDashboard::make()->asInertia();
    }




}
