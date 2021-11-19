<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 19 Nov 2021 23:43:51 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Sales\Charge;

use App\Actions\Migrations\MigrationResult;
use App\Actions\WithUpdate;
use App\Models\Sales\Charge;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateCharge
{
    use AsAction;
    use WithUpdate;

    public function handle(Charge $charge, array $modelData): MigrationResult
    {
        $res = new MigrationResult();

        $charge->update(Arr::except($modelData, ['data']));
        $charge->update($this->extractJson($modelData, ['data']));

        $res->changes = array_merge($res->changes, $charge->getChanges());


        $res->model    = $charge;
        $res->model_id = $charge->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
