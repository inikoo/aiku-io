<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 29 Oct 2021 12:56:07 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\Stock;

use App\Models\Account\Tenant;
use App\Models\CRM\FulfilmentCustomer;
use App\Models\Utils\ActionResult;
use Lorisleiva\Actions\Concerns\AsAction;


class StoreStock
{
    use AsAction;

    public function handle(Tenant|FulfilmentCustomer $owner,$modelData): ActionResult
    {
        $res  = new ActionResult();

        /** @var \App\Models\Inventory\Stock $stock */
        $stock= $owner->stocks()->create($modelData);

        $res->model    = $stock;
        $res->model_id = $stock->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
