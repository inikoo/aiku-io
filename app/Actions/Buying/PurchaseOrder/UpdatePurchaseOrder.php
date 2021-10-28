<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 28 Oct 2021 00:32:26 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Buying\PurchaseOrder;

use App\Actions\Migrations\MigrationResult;
use App\Actions\WithUpdate;
use App\Models\Buying\PurchaseOrder;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdatePurchaseOrder
{
    use AsAction;
    use WithUpdate;

    public function handle(PurchaseOrder $purchaseOrder,array $modelData): MigrationResult
    {
        $res = new MigrationResult();
        $purchaseOrder->update( Arr::except($modelData, ['data']));
        $purchaseOrder->update($this->extractJson($modelData));


        $res->changes = array_merge($res->changes, $purchaseOrder->getChanges());

        $res->model    = $purchaseOrder;
        $res->model_id = $purchaseOrder->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
