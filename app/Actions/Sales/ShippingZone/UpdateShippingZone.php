<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 19 Nov 2021 14:11:11 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Sales\ShippingZone;

use App\Actions\Migrations\MigrationResult;
use App\Actions\WithUpdate;
use App\Models\Sales\ShippingZone;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateShippingZone
{
    use AsAction;
    use WithUpdate;

    public function handle(ShippingZone $shippingZone, array $modelData): MigrationResult
    {
        $res = new MigrationResult();

        $shippingZone->update(Arr::except($modelData, ['price', 'territories']));
        $shippingZone->update($this->extractJson($modelData, ['price', 'territories']));

        $res->changes = array_merge($res->changes, $shippingZone->getChanges());


        $res->model    = $shippingZone;
        $res->model_id = $shippingZone->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
