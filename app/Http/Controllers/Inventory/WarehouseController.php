<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 15 Jan 2022 22:26:07 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Inventory;


use App\Actions\Inventory\Warehouse\IndexWarehouse;
use App\Actions\Inventory\Warehouse\ShowEditWarehouse;
use App\Actions\Inventory\Warehouse\ShowWarehouse;
use App\Actions\Inventory\Warehouse\UpdateWarehouse;
use App\Http\Controllers\Controller;
use App\Models\Inventory\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function edit(Warehouse $warehouse): Response
    {

        return ShowEditWarehouse::make()->asInertia(warehouse: $warehouse);
    }

    public function update(Warehouse $warehouse, Request $request): RedirectResponse
    {
        return UpdateWarehouse::make()->asInertia(warehouse: $warehouse,request:  $request);
    }

}
