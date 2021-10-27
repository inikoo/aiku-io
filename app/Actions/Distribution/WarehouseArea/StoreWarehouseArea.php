<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 04 Oct 2021 12:34:45 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Distribution\WarehouseArea;

use App\Actions\Migrations\MigrationResult;
use App\Models\Distribution\Warehouse;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreWarehouseArea
{
    use AsAction;

    public function handle(Warehouse $warehouse, array $data): MigrationResult
    {
        $res  = new MigrationResult();

        /** @var \App\Models\Distribution\WarehouseArea $warehouseArea */
        $warehouseArea= $warehouse->areas()->create($data);
        $res->model    = $warehouseArea;
        $res->model_id = $warehouseArea->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
