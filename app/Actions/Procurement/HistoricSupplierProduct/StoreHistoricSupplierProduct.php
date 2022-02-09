<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 09 Feb 2022 04:59:07 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\HistoricSupplierProduct;

use App\Models\Procurement\SupplierProduct;
use App\Models\Utils\ActionResult;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreHistoricSupplierProduct
{
    use AsAction;

    public function handle(SupplierProduct $supplierProduct, array $data): ActionResult
    {
        $res = new ActionResult();

        /** @var \App\Models\Procurement\HistoricSupplierProduct $historicSupplierProduct */
        $historicSupplierProduct = $supplierProduct->historicRecords()->create($data);

        $res->model    = $historicSupplierProduct;
        $res->model_id = $historicSupplierProduct->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
