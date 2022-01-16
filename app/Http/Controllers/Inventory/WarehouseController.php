<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 15 Jan 2022 22:26:07 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Inventory;


use App\Actions\Inventory\Warehouse\IndexWarehouse;
use App\Actions\Inventory\Warehouse\ShowWarehouse;
use App\Http\Controllers\Controller;
use App\Models\Inventory\Warehouse;
use Inertia\Response;


class WarehouseController extends Controller
{


    public function index(): Response
    {
        return IndexWarehouse::make()->asInertia();
    }

    public function show(Warehouse $warehouse): Response
    {
        return ShowWarehouse::make()->asInertia($warehouse);
    }


}
