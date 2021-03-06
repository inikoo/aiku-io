<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 01 Dec 2021 00:03:19 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Sales\Adjust;

use App\Models\Utils\ActionResult;
use App\Models\Marketing\Shop;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreAdjust
{
    use AsAction;

    public function handle(Shop $shop, array $data): ActionResult
    {
        $res = new ActionResult();

        /** @var \App\Models\Sales\Adjust $adjust */
        $adjust = $shop->adjusts()->create($data);

        $res->model    = $adjust;
        $res->model_id = $adjust->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
