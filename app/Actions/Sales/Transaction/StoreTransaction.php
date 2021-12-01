<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 25 Nov 2021 21:49:17 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Sales\Transaction;

use App\Actions\Migrations\MigrationResult;
use App\Models\Sales\Order;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreTransaction
{
    use AsAction;

    public function handle(Order $order, array $data): MigrationResult
    {
        $res = new MigrationResult();

        /** @var \App\Models\Sales\Transaction $transaction */
        $transaction = $order->transactions()->create($data);

        $res->model    = $transaction;
        $res->model_id = $transaction->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
