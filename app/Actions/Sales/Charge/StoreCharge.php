<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 19 Nov 2021 23:50:11 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Sales\Charge;

use App\Models\Utils\ActionResult;
use App\Models\Marketing\Shop;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreCharge
{
    use AsAction;

    public function handle(Shop $shop, array $data): ActionResult
    {
        $res = new ActionResult();



        /** @var \App\Models\Sales\Charge $charge */
        $charge = $shop->charges()->create($data);


        $res->model    = $charge;
        $res->model_id = $charge->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
