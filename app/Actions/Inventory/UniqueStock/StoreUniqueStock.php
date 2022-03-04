<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 06 Jan 2022 15:57:45 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\UniqueStock;


use App\Models\CRM\Customer;
use App\Models\Utils\ActionResult;
use Lorisleiva\Actions\Concerns\AsAction;


class StoreUniqueStock
{
    use AsAction;

    public function handle(Customer $owner,$modelData): ActionResult
    {
        $res  = new ActionResult();

        /** @var \App\Models\Inventory\Stock $stock */
        $stock= $owner->uniqueStocks()->create($modelData);

        $res->model    = $stock;
        $res->model_id = $stock->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
