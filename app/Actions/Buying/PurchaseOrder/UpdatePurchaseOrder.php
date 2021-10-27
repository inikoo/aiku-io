<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 28 Oct 2021 00:32:26 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Buying\PurchaseOrder;

use App\Actions\Migrations\MigrationResult;
use App\Models\Buying\PurchaseOrder;
use App\Models\Buying\Supplier;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdatePurchaseOrder
{
    use AsAction;

    public function handle(PurchaseOrder $purchaseOrder,array $data): MigrationResult
    {
        $res = new MigrationResult();
        $purchaseOrder->update($data);
        $res->changes = array_merge($res->changes, $purchaseOrder->getChanges());

        $res->model    = $purchaseOrder;
        $res->model_id = $purchaseOrder->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
