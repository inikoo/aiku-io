<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 06 Jan 2022 15:58:32 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\UniqueStock;

use App\Models\Inventory\UniqueStock;
use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateUniqueStock
{
    use AsAction;
    use WithUpdate;

    public function handle(UniqueStock $uniqueStock, array $modelData): ActionResult
    {
        $res = new ActionResult();


        $uniqueStock->update(Arr::except($modelData, []));


        $res->changes = array_merge($res->changes, $uniqueStock->getChanges());

        $res->model    = $uniqueStock;
        $res->model_id = $uniqueStock->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
