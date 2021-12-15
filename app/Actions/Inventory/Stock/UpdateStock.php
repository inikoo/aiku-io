<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 29 Oct 2021 12:59:10 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\Stock;

use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use App\Models\Inventory\Stock;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateStock
{
    use AsAction;
    use WithUpdate;

    public function handle(Stock $stock, array $modelData): ActionResult
    {
        $res = new ActionResult();


        $stock->update(Arr::except($modelData, ['data', 'settings']));
        $stock->update($this->extractJson($modelData, ['data', 'settings']));

        $res->changes = array_merge($res->changes, $stock->getChanges());

        $res->model    = $stock;
        $res->model_id = $stock->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
