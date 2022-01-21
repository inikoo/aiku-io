<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 20 Jan 2022 17:53:16 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Inventory;


use App\Actions\Inventory\Warehouse\IndexWarehouse;
use App\Actions\Inventory\Warehouse\ShowWarehouse;
use App\Http\Controllers\Controller;
use App\Models\Inventory\Location;
use App\Models\Inventory\Warehouse;
use Inertia\Response;


class LocationController extends Controller
{


    public function index(): Response
    {
        return IndexLocation::make()->asInertia();
    }

    public function show(Warehouse $warehouse,Location $location): Response
    {
        return ShowLocation::make()->asInertia($warehouse,$location);
    }


}
