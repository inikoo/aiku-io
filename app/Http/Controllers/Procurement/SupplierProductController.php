<?php /*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 17 Mar 2022 04:33:28 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */ /** @noinspection PhpUnusedParameterInspection */


namespace App\Http\Controllers\Procurement;



use App\Actions\Procurement\SupplierProduct\IndexSupplierProductInSupplier;
use App\Actions\Procurement\SupplierProduct\IndexSupplierProductInTenant;
use App\Actions\Procurement\SupplierProduct\IndexSupplierProductInTenantWithAvailabilityStatus;
use App\Enums\AvailabilityStatus;
use App\Http\Controllers\Controller;

use App\Models\Procurement\Supplier;
use Inertia\Response;


class SupplierProductController extends Controller
{


    public function indexInTenant(): Response
    {
        return IndexSupplierProductInTenant::make()->asInertia();
    }

    public function indexInTenantWithAvailabilityStatus(AvailabilityStatus $availabilityStatus): Response
    {
        return  IndexSupplierProductInTenantWithAvailabilityStatus::make()->asInertia($availabilityStatus);
    }

    public function indexInSupplier(Supplier $supplier): Response
    {
        return IndexSupplierProductInSupplier::make()->asInertia($supplier);
    }

    public function show(): Response
    {
        return ShowSupplierProductInTenant::make()->asInertia();
    }


    public function showWithAvailabilityStatus(AvailabilityStatus $availabilityStatus): Response
    {
        return ShowSupplierProductWithAvailabilityStatus::make()->asInertia($availabilityStatus);
    }

    /*
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
