<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 07 Dec 2021 17:35:30 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\StockMovement;

use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use App\Models\Inventory\StockMovement;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateStockMovement
{
    use AsAction;
    use WithUpdate;

    public function handle(
        StockMovement $stockMovement,
        array $modelData
    ): ActionResult {
        $res = new ActionResult();


        $stockMovement->update(Arr::except($modelData, ['data']));
        $stockMovement->update($this->extractJson($modelData, ['data']));

        $res->changes = array_merge($res->changes, $stockMovement->getChanges());

        $res->model    = $stockMovement;
        $res->model_id = $stockMovement->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
