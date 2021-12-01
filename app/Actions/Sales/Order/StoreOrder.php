<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 25 Nov 2021 16:15:50 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Sales\Order;

use App\Actions\Migrations\MigrationResult;
use App\Models\Helpers\Address;
use App\Models\Trade\Shop;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreOrder
{
    use AsAction;

    public function handle(
        Shop $shop,
        array $modelData,
        Address $invoiceAddress,
        Address $deliveryAddress

    ): MigrationResult
    {
        $res = new MigrationResult();

        /** @var \App\Models\Sales\Order $order */
        $order = $shop->orders()->create($modelData);



        $res->model    = $order;
        $res->model_id = $order->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
