<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 29 Oct 2021 12:59:10 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Trade\TradeUnit;

use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use App\Models\Trade\TradeUnit;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateTradeUnit
{
    use AsAction;
    use WithUpdate;

    public function handle(TradeUnit $unit, array $modelData): ActionResult
    {
        $res = new ActionResult();


        $unit->update(Arr::except($modelData, ['data', 'dimensions']));
        $unit->update($this->extractJson($modelData, ['data', 'dimensions']));

        $res->changes = array_merge($res->changes, $unit->getChanges());

        $res->model    = $unit;
        $res->model_id = $unit->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
