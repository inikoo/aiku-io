<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 10 Dec 2021 14:11:55 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Delivery\DeliveryNoteItem;

use App\Models\Delivery\DeliveryNote;
use App\Models\Utils\ActionResult;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreDeliveryNoteItem
{
    use AsAction;

    public function handle(DeliveryNote $deliveryNote, array $modelData): ActionResult
    {
        $res = new ActionResult();


        /** @var \App\Models\Delivery\DeliveryNoteItem $deliveryNoteItem */
        $deliveryNoteItem = $deliveryNote->deliveryNoteItems()->create($modelData);

        $res->model    = $deliveryNoteItem;
        $res->model_id = $deliveryNoteItem->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
