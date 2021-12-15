<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 29 Oct 2021 12:56:07 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\Stock;

use App\Models\Utils\ActionResult;
use App\Models\Inventory\Stock;
use Lorisleiva\Actions\Concerns\AsAction;


class StoreStock
{
    use AsAction;

    public function handle($modelData): ActionResult
    {
        $res  = new ActionResult();

        $stock= Stock::create($modelData);



        $res->model    = $stock;
        $res->model_id = $stock->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
