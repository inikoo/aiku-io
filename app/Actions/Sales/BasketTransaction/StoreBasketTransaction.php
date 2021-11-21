<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 17 Nov 2021 03:31:53 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Sales\BasketTransaction;

use App\Actions\Migrations\MigrationResult;
use App\Models\Sales\Basket;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreBasketTransaction
{
    use AsAction;

    public function handle(Basket $basket, array $data): MigrationResult
    {
        $res = new MigrationResult();

        /** @var \App\Models\Sales\BasketTransaction $transaction */
        $transaction = $basket->transactions()->create($data);

        $res->model    = $transaction;
        $res->model_id = $transaction->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
