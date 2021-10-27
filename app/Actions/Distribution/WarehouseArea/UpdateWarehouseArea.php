<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 04 Oct 2021 12:35:41 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Distribution\WarehouseArea;

use App\Actions\Migrations\MigrationResult;
use App\Models\Distribution\WarehouseArea;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateWarehouseArea
{
    use AsAction;

    public function handle(WarehouseArea $area, array $data): MigrationResult
    {
        $res = new MigrationResult();

        $area->update($data);
        $res->model    = $area;
        $res->model_id = $area->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
