<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 17 Mar 2022 21:56:11 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\ProcurementDelivery;

use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use App\Models\Procurement\ProcurementDelivery;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateProcurementDelivery
{
    use AsAction;
    use WithUpdate;

    public function handle(ProcurementDelivery $procurementDelivery,array $modelData): ActionResult
    {
        $res = new ActionResult();
        $procurementDelivery->update( Arr::except($modelData, ['data']));
        $procurementDelivery->update($this->extractJson($modelData));


        $res->changes = array_merge($res->changes, $procurementDelivery->getChanges());

        $res->model    = $procurementDelivery;
        $res->model_id = $procurementDelivery->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
