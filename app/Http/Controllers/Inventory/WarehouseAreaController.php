<?php
/** @noinspection PhpUnusedParameterInspection */

/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 31 Jan 2022 03:38:07 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Inventory;


use App\Actions\Inventory\WarehouseArea\IndexWarehouseAreaInWarehouse;
use App\Actions\Inventory\WarehouseArea\ShowEditWarehouseArea;
use App\Actions\Inventory\WarehouseArea\ShowWarehouseArea;
use App\Actions\Inventory\WarehouseArea\UpdateWarehouseArea;
use App\Http\Controllers\Controller;
use App\Models\Inventory\Warehouse;
use App\Models\Inventory\WarehouseArea;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;


class WarehouseAreaController extends Controller
{


    public function indexInWarehouse(Warehouse $warehouse): Response
    {
        return IndexWarehouseAreaInWarehouse::make()->asInertia($warehouse);
    }

    public function show(WarehouseArea $warehouseArea): Response
    {
        return ShowWarehouseArea::make()->asInertia(parent: 'tenant', warehouseArea: $warehouseArea);
    }

    public function showInWarehouse(Warehouse $warehouse, WarehouseArea $warehouseArea): Response
    {
        return ShowWarehouseArea::make()->asInertia(parent: 'warehouse', warehouseArea: $warehouseArea);
    }

    public function edit( WarehouseArea $warehouseArea): Response
    {
        return ShowEditWarehouseArea::make()->asInertia(parent: 'tenant',  warehouseArea: $warehouseArea);
    }

    public function editInWarehouse(Warehouse $warehouse, WarehouseArea $warehouseArea): Response
    {
        return ShowEditWarehouseArea::make()->asInertia(parent: 'warehouse',  warehouseArea: $warehouseArea);
    }

    public function update(WarehouseArea $warehouseArea, Request $request): RedirectResponse
    {
        return UpdateWarehouseArea::make()->asInertia($warehouseArea, $request);
    }

}
