<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 10 Dec 2021 14:26:34 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Delivery\DeliveryNoteItem;

use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use App\Models\Delivery\DeliveryNoteItem;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateDeliveryNoteItem
{
    use AsAction;
    use WithUpdate;

    public function handle(DeliveryNoteItem $deliveryNoteItem, array $modelData): ActionResult
    {
        $res = new ActionResult();

        $deliveryNoteItem->update( Arr::except($modelData, ['data']));
        $deliveryNoteItem->update($this->extractJson($modelData));

        $res->changes = array_merge($res->changes, $deliveryNoteItem->getChanges());

        $res->model    = $deliveryNoteItem;
        $res->model_id = $deliveryNoteItem->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';
        return $res;
    }
}
