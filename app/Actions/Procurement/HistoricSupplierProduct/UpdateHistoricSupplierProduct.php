<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 09 Feb 2022 05:00:14 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\HistoricSupplierProduct;

use App\Models\Procurement\HistoricSupplierProduct;
use App\Models\Utils\ActionResult;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateHistoricSupplierProduct
{
    use AsAction;

    public function handle(HistoricSupplierProduct $historicSupplierProduct, array $data): ActionResult
    {
        $res = new ActionResult();

        $historicSupplierProduct->update($data);
        $res->changes = array_merge($res->changes, $historicSupplierProduct->getChanges());


        $res->model    = $historicSupplierProduct;
        $res->model_id = $historicSupplierProduct->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
