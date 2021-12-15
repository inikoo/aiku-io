<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 19 Nov 2021 04:10:36 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Sales\ShippingZone;

use App\Models\Utils\ActionResult;
use App\Models\Sales\ShippingSchema;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreShippingZone
{
    use AsAction;

    public function handle(ShippingSchema $shippingSchema, array $data): ActionResult
    {
        $res = new ActionResult();



        /** @var \App\Models\Sales\ShippingZone $shippingZone */
        $shippingZone = $shippingSchema->shippingZone()->create($data);


        $res->model    = $shippingZone;
        $res->model_id = $shippingZone->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
