<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 04 Oct 2021 12:34:45 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\WarehouseArea;

use App\Models\Utils\ActionResult;
use App\Models\Inventory\Warehouse;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreWarehouseArea
{
    use AsAction;

    public function handle(Warehouse $warehouse, array $data): ActionResult
    {
        $res  = new ActionResult();

        /** @var \App\Models\Inventory\WarehouseArea $warehouseArea */
        $warehouseArea= $warehouse->warehouseAreas()->create($data);
        $warehouseArea->stats()->create();
        $res->model    = $warehouseArea;
        $res->model_id = $warehouseArea->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
