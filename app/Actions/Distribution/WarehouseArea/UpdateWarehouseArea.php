<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 04 Oct 2021 12:35:41 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Distribution\WarehouseArea;

use App\Actions\Migrations\MigrationResult;
use App\Actions\WithUpdate;
use App\Models\Distribution\WarehouseArea;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateWarehouseArea
{
    use AsAction;
    use WithUpdate;

    public function handle(WarehouseArea $area, array $modelData): MigrationResult
    {
        $res = new MigrationResult();

        $area->update(Arr::except($modelData, ['data']));
        $area->update($this->extractJson($modelData));
        $res->changes = array_merge($res->changes, $area->getChanges());

        $res->model    = $area;
        $res->model_id = $area->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
