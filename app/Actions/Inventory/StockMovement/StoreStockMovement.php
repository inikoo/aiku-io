<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 07 Dec 2021 17:30:01 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\StockMovement;

use App\Models\Inventory\Stock;
use App\Models\Inventory\UniqueStock;
use App\Models\Utils\ActionResult;
use Lorisleiva\Actions\Concerns\AsAction;


class StoreStockMovement
{
    use AsAction;

    public function handle(Stock|UniqueStock $stockable, array $modelData): ActionResult
    {
        $res  = new ActionResult();

        /** @var \App\Models\Inventory\StockMovement $stockMovement */
        $stockMovement = $stockable->stockMovements()->create($modelData);
        $res->model    = $stockMovement;
        $res->model_id = $stockMovement->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }


}
