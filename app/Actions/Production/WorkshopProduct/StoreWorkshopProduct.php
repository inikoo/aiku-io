<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 09 Feb 2022 03:52:32 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Production\WorkshopProduct;

use App\Models\Production\Workshop;
use App\Models\Utils\ActionResult;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreWorkshopProduct
{
    use AsAction;

    public function handle(Workshop $workshop, array $data): ActionResult
    {
        $res = new ActionResult();

        /** @var \App\Models\Production\WorkshopProduct $workshopProduct */
        $workshopProduct = $workshop->workshopProducts()->create($data);

        $workshopProduct->salesStats()->create([
                                                   'scope' => 'sales-tenant-currency'
                                               ]);


        $res->model    = $workshopProduct;
        $res->model_id = $workshopProduct->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
