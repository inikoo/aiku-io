<?php
/** @noinspection PhpUnusedParameterInspection */

/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 20 Jan 2022 17:53:16 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Inventory;


use App\Actions\Inventory\Location\IndexLocationInTenant;
use App\Actions\Inventory\Location\IndexLocationInWarehouse;

use App\Actions\Inventory\Location\IndexLocationInWarehouseArea;
use App\Actions\Inventory\Location\IndexLocationInWarehouseAreaInWarehouse;
use App\Actions\Inventory\Location\ShowLocation;
use App\Actions\Inventory\Location\ShowEditLocation;

use App\Actions\Inventory\Location\UpdateLocation;
use App\Http\Controllers\Controller;
use App\Models\Inventory\Location;
use App\Models\Inventory\Warehouse;
use App\Models\Inventory\WarehouseArea;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;


class LocationController extends Controller
{


    public function indexInTenant(): Response
    {
        return IndexLocationInTenant::make()->asInertia();
    }

    public function indexInWarehouse(Warehouse $warehouse): Response
    {
        return IndexLocationInWarehouse::make()->asInertia($warehouse);
    }

    public function indexInArea(WarehouseArea $warehouseArea): Response
    {
        return IndexLocationInWarehouseArea::make()->asInertia(warehouseArea: $warehouseArea);
    }

    public function indexInAreaInWarehouse(Warehouse $warehouse, WarehouseArea $warehouseArea): Response
    {
        return IndexLocationInWarehouseAreaInWarehouse::make()->asInertia(warehouseArea: $warehouseArea);
    }


    public function showInWarehouse(Warehouse $warehouse, Location $location): Response
    {
        return ShowLocation::make()->asInertia(parent: 'warehouse', location: $location);
    }

    public function showInAreaInWarehouse(Warehouse $warehouse, WarehouseArea $warehouseArea, Location $location): Response
    {
        return ShowLocation::make()->asInertia(parent: 'warehouseAreaInWarehouse', location: $location);
    }

    public function showInArea(WarehouseArea $warehouseArea, Location $location): Response
    {
        return ShowLocation::make()->asInertia(parent: 'warehouseArea', location: $location);
    }

    public function showInTenant(Location $location): Response
    {
        return ShowLocation::make()->asInertia(parent: 'tenant', location: $location);
    }


    public function editInWarehouse(Warehouse $warehouse, Location $location): Response
    {
        return ShowEditLocation::make()->asInertia(parent: 'warehouse', location: $location);
    }

    public function editInAreaInWarehouse(Warehouse $warehouse, WarehouseArea $warehouseArea, Location $location): Response
    {
        return ShowEditLocation::make()->asInertia(parent: 'warehouseAreaInWarehouse', location: $location);
    }

    public function editInArea(Warehouse $warehouse, WarehouseArea $warehouseArea, Location $location): Response
    {
        return ShowEditLocation::make()->asInertia(parent: 'warehouseArea', location: $location);
    }

    public function editInTenant(Location $location): Response
    {
        return ShowEditLocation::make()->asInertia(parent: 'tenant', location: $location);
    }


    public function update(Location $location, Request $request): RedirectResponse
    {
        return UpdateLocation::make()->asInertia($location, $request);
    }


}
