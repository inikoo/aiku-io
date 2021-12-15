<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 04 Oct 2021 11:48:37 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\Warehouse;

use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use App\Models\Inventory\Warehouse;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateWarehouse
{
    use AsAction;
    use WithUpdate;

    public function handle(Warehouse $warehouse, array $modelData): ActionResult
    {
        $res = new ActionResult();


        $warehouse->update(Arr::except($modelData, ['data', 'settings']));
        $warehouse->update($this->extractJson($modelData, ['data', 'settings']));

        $res->changes = array_merge($res->changes, $warehouse->getChanges());

        $res->model    = $warehouse;
        $res->model_id = $warehouse->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
