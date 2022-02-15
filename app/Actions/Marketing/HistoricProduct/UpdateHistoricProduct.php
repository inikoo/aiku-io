<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 29 Sep 2021 16:47:46 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Marketing\HistoricProduct;

use App\Models\Utils\ActionResult;
use App\Models\Marketing\HistoricProduct;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateHistoricProduct
{
    use AsAction;

    public function handle(HistoricProduct $historicProduct, array $data): ActionResult
    {
        $res = new ActionResult();


        $historicProduct->update($data);
        $res->changes = array_merge($res->changes, $historicProduct->getChanges());


        $res->model    = $historicProduct;
        $res->model_id = $historicProduct->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
