<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 09 Feb 2022 04:57:49 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Production\HistoricWorkshopProduct;

use App\Models\Production\WorkshopProduct;
use App\Models\Utils\ActionResult;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreHistoricWorkshopProduct
{
    use AsAction;

    public function handle(WorkshopProduct $workshopProduct, array $data): ActionResult
    {
        $res = new ActionResult();

        /** @var \App\Models\Production\HistoricWorkshopProduct $historicWorkshopProduct */
        $historicWorkshopProduct = $workshopProduct->historicRecords()->create($data);

        $res->model    = $historicWorkshopProduct;
        $res->model_id = $historicWorkshopProduct->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
