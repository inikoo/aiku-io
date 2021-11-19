<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 19 Nov 2021 02:45:20 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Sales\ShippingSchema;

use App\Actions\Migrations\MigrationResult;
use App\Actions\WithUpdate;

use App\Models\Sales\ShippingSchema;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateShippingSchema
{
    use AsAction;
    use WithUpdate;

    public function handle(ShippingSchema $shippingSchema, array $modelData): MigrationResult
    {
        $res = new MigrationResult();


        $shippingSchema->update($modelData);

        $res->changes = array_merge($res->changes, $shippingSchema->getChanges());


        $res->model    = $shippingSchema;
        $res->model_id = $shippingSchema->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
