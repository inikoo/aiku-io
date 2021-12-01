<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 25 Nov 2021 16:23:34 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Sales\Order;

use App\Actions\Migrations\MigrationResult;
use App\Actions\WithUpdate;
use App\Models\Helpers\Address;
use App\Models\Sales\Order;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateOrder
{
    use AsAction;
    use WithUpdate;

    public function handle(
        Order $order,
        array $modelData,
        Address $invoiceAddress,
        Address $deliveryAddress
    ): MigrationResult
    {
        $res = new MigrationResult();




        $order->update( Arr::except($modelData, ['data']));
        $order->update($this->extractJson($modelData));

        $res->changes = array_merge($res->changes, $order->getChanges());





        $res->model    = $order;
        $res->model_id = $order->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
