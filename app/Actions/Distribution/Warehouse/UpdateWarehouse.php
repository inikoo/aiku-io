<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 04 Oct 2021 11:48:37 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Distribution\Warehouse;

use App\Actions\Migrations\MigrationResult;
use App\Models\Distribution\Warehouse;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateWarehouse
{
    use AsAction;

    public function handle(Warehouse $warehouse, array $data): MigrationResult
    {
        $res = new MigrationResult();

        $warehouse->update($data);
        $res->changes = array_merge($res->changes, $warehouse->getChanges());

        $res->model    = $warehouse;
        $res->model_id = $warehouse->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
