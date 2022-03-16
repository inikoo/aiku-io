<?php /** @noinspection PhpUnusedParameterInspection */

/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 20 Jan 2022 16:48:29 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Inventory;


use App\Actions\Inventory\Stock\IndexActiveStockInTenant;
use App\Actions\Inventory\Stock\IndexDiscontinuedStockInTenant;
use App\Actions\Inventory\Stock\IndexDiscontinuingStockInTenant;
use App\Actions\Inventory\Stock\IndexInProcessStockInTenant;
use App\Actions\Inventory\Stock\IndexStockInLocation;
use App\Actions\Inventory\Stock\IndexStockInTenant;
use App\Actions\Inventory\Stock\ShowStock;
use App\Enums\StockState;
use App\Http\Controllers\Controller;
use App\Models\Inventory\Location;
use App\Models\Inventory\Stock;
use App\Models\Inventory\Warehouse;
use App\Models\Inventory\WarehouseArea;
use Inertia\Response;


class StockController extends Controller
{


    public function indexInTenant(): Response
    {
        return IndexStockInTenant::make()->asInertia();
    }

    public function indexInTenantWithState(StockState $stockState): Response
    {
       return match ($stockState->name){
           'Active'=> IndexActiveStockInTenant::make()->asInertia(),
           'InProcess'=> IndexInProcessStockInTenant::make()->asInertia(),
           'Discontinuing'=> IndexDiscontinuingStockInTenant::make()->asInertia(),
           'Discontinued'=> IndexDiscontinuedStockInTenant::make()->asInertia()

       };

    }

    public function indexInLocationInWarehouse(Warehouse $warehouse, Location $location): Response
    {
        return IndexStockInLocation::make()->asInertia(parent:'warehouse',location:$location);
    }

    public function indexInLocationInArea(Warehouse $warehouse,WarehouseArea $warehouseArea, Location $location): Response
    {
        return IndexStockInLocation::make()->asInertia(parent:'warehouseAreaInWarehouse',location:$location);
    }

    public function show(Stock $stock): Response
    {
        return ShowStock::make()->asInertia($stock);
    }


}
