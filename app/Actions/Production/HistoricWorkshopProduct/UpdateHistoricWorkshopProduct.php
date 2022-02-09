<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 09 Feb 2022 04:57:57 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Production\HistoricWorkshopProduct;

use App\Models\Production\HistoricWorkshopProduct;
use App\Models\Utils\ActionResult;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateHistoricWorkshopProduct
{
    use AsAction;

    public function handle(HistoricWorkshopProduct $historicWorkshopProduct, array $data): ActionResult
    {
        $res = new ActionResult();


        $historicWorkshopProduct->update($data);
        $res->changes = array_merge($res->changes, $historicWorkshopProduct->getChanges());


        $res->model    = $historicWorkshopProduct;
        $res->model_id = $historicWorkshopProduct->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
