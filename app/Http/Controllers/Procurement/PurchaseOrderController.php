<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 18 Mar 2022 06:14:26 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Procurement;

use App\Actions\Procurement\PurchaseOrder\ByState\IndexCancelledPurchaseOrderInTenant;
use App\Actions\Procurement\PurchaseOrder\ByState\IndexConfirmedPurchaseOrderInTenant;
use App\Actions\Procurement\PurchaseOrder\ByState\IndexDeliveredPurchaseOrderInTenant;
use App\Actions\Procurement\PurchaseOrder\ByState\IndexDispatchedPurchaseOrderInTenant;
use App\Actions\Procurement\PurchaseOrder\ByState\IndexSubmittedPurchaseOrderInTenant;
use App\Actions\Procurement\PurchaseOrder\IndexPurchaseOrderInSupplier;
use App\Actions\Procurement\PurchaseOrder\IndexPurchaseOrderInTenant;
use App\Actions\Procurement\PurchaseOrder\ByState\IndexInProcessPurchaseOrderInTenant;
use App\Enums\PurchaseOrderState;
use App\Http\Controllers\Controller;
use App\Models\Procurement\Supplier;
use Inertia\Response;


class PurchaseOrderController extends Controller
{


    public function indexInTenant(): Response
    {
        return IndexPurchaseOrderInTenant::make()->asInertia();
    }

    public function indexInTenantWithState(PurchaseOrderState $purchaseOrderState): Response
    {
        return match ($purchaseOrderState->name) {
            'InProcess' => IndexInProcessPurchaseOrderInTenant::make()->asInertia($purchaseOrderState),
            'Submitted' => IndexSubmittedPurchaseOrderInTenant::make()->asInertia($purchaseOrderState),
            'Confirmed' => IndexConfirmedPurchaseOrderInTenant::make()->asInertia($purchaseOrderState),
            'Dispatched' => IndexDispatchedPurchaseOrderInTenant::make()->asInertia($purchaseOrderState),
            'Delivered' => IndexDeliveredPurchaseOrderInTenant::make()->asInertia($purchaseOrderState),
            'Cancelled' => IndexCancelledPurchaseOrderInTenant::make()->asInertia($purchaseOrderState)
        };
    }


    public function indexInSupplier(Supplier $supplier): Response
    {
        return IndexPurchaseOrderInSupplier::make()->asInertia(supplier: $supplier);
    }


}
