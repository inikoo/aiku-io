<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 29 Oct 2021 15:47:36 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Trade\TradeUnit;

use App\Models\Utils\ActionResult;
use App\Models\Trade\TradeUnit;
use Lorisleiva\Actions\Concerns\AsAction;


class StoreTradeUnit
{
    use AsAction;

    public function handle($modelData): ActionResult
    {
        $res  = new ActionResult();

        $unit= TradeUnit::create($modelData);

        $res->model    = $unit;
        $res->model_id = $unit->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
