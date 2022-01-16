<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 27 Oct 2021 22:36:22 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\PurchaseOrder;

use App\Actions\Helpers\Address\StoreAddress;
use App\Models\Utils\ActionResult;
use App\Models\Account\Tenant;
use App\Models\Aiku\Aiku;
use App\Models\Procurement\Agent;
use App\Models\Procurement\Supplier;
use Lorisleiva\Actions\Concerns\AsAction;

class StorePurchaseOrder
{
    use AsAction;

    public function handle(Agent|Supplier $parent, array $data): ActionResult
    {
        $res  = new ActionResult();

        /** @var \App\Models\Procurement\PurchaseOrder $purchaseOrder */
        $purchaseOrder = $parent->purchaseOrders()->create($data);


        $res->model    = $purchaseOrder;
        $res->model_id = $purchaseOrder->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;    }
}
