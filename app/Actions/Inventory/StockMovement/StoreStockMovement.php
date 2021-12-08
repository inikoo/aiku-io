<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 07 Dec 2021 17:30:01 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\StockMovement;

use App\Actions\Migrations\MigrationResult;
use App\Models\Inventory\StockMovement;
use Lorisleiva\Actions\Concerns\AsAction;


class StoreStockMovement
{
    use AsAction;

    public function handle(array $modelData): MigrationResult
    {
        $res  = new MigrationResult();

        $stockMovement = StockMovement::create($modelData);
        $res->model    = $stockMovement;
        $res->model_id = $stockMovement->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }


}
