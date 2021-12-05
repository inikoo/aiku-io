<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 05 Dec 2021 15:42:26 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Delivery\DeliveryNote;

use App\Actions\Migrations\MigrationResult;
use App\Actions\WithUpdate;
use App\Models\Delivery\DeliveryNote;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateDeliveryNote
{
    use AsAction;
    use WithUpdate;

    public function handle(
        DeliveryNote $deliveryNote,
        array $modelData,
    ): MigrationResult
    {
        $res = new MigrationResult();

        $deliveryNote->update( Arr::except($modelData, ['data']));
        $deliveryNote->update($this->extractJson($modelData));

        $res->changes = array_merge($res->changes, $deliveryNote->getChanges());

        $res->model    = $deliveryNote;
        $res->model_id = $deliveryNote->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
