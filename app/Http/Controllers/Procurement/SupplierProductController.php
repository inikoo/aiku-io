<?php /*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 17 Mar 2022 04:33:28 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */ /** @noinspection PhpUnusedParameterInspection */


namespace App\Http\Controllers\Procurement;



use App\Http\Controllers\Controller;

use Inertia\Response;


class SupplierProductController extends Controller
{


    public function indexInTenant(): Response
    {
        return IndexSupplierProductInTenant::make()->asInertia();
    }

    /*
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
*/

}
