<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 10 Dec 2021 14:11:55 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Delivery\DeliveryNoteItem;

use App\Actions\Migrations\MigrationResult;
use App\Models\Sales\Transaction;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreDeliveryNoteItem
{
    use AsAction;

    public function handle(Transaction $transaction, array $modelData): MigrationResult
    {
        $res = new MigrationResult();


        /** @var \App\Models\Delivery\DeliveryNoteItem $deliveryNoteItem */
        $deliveryNoteItem = $transaction->deliveryNoteItems()->create($modelData);

        $res->model    = $deliveryNoteItem;
        $res->model_id = $deliveryNoteItem->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}