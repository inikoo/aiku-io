<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 09 Dec 2021 14:36:14 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Delivery\Picking;

use App\Models\Utils\ActionResult;
use App\Models\Delivery\DeliveryNote;
use App\Models\Sales\Order;
use App\Models\Sales\Transaction;
use Lorisleiva\Actions\Concerns\AsAction;

class StorePicking
{
    use AsAction;

    public function handle(DeliveryNote $deliveryNote, array $modelData): ActionResult
    {
        $res = new ActionResult();



        /** @var \App\Models\Delivery\Picking $picking */
        $picking = $deliveryNote->pickings()->create($modelData);

        $res->model    = $picking;
        $res->model_id = $picking->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
