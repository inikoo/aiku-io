<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 17 Mar 2022 21:42:00 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\ProcurementDelivery;

use App\Models\Utils\ActionResult;
use App\Models\Procurement\Agent;
use App\Models\Procurement\Supplier;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreProcurementDelivery
{
    use AsAction;

    public function handle(Agent|Supplier $parent, array $data): ActionResult
    {
        $res  = new ActionResult();

        /** @var \App\Models\Procurement\ProcurementDelivery $procurementDelivery */
        $procurementDelivery = $parent->procurementDeliveries()->create($data);


        $res->model    = $procurementDelivery;
        $res->model_id = $procurementDelivery->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;    }
}
