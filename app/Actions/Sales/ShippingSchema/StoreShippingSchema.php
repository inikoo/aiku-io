<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 19 Nov 2021 02:42:54 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Sales\ShippingSchema;

use App\Models\Utils\ActionResult;
use App\Models\Trade\Shop;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreShippingSchema
{
    use AsAction;

    public function handle(Shop $shop, array $data): ActionResult
    {
        $res = new ActionResult();


        /** @var \App\Models\Sales\ShippingSchema $shippingShema */
        $shippingSchema = $shop->shippingSchema()->create($data);



        $res->model    = $shippingSchema;
        $res->model_id = $shippingSchema->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
