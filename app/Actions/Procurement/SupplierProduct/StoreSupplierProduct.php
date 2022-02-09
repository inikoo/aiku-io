<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 09 Feb 2022 03:40:23 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\SupplierProduct;

use App\Models\Utils\ActionResult;
use App\Models\Procurement\Supplier;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreSupplierProduct
{
    use AsAction;

    public function handle(Supplier $supplier, array $data): ActionResult
    {
        $res = new ActionResult();

        if ($supplier->owner_type == 'Agent') {
            $data['agent_id'] = $supplier->owner_id;
        }

        /** @var \App\Models\Procurement\SupplierProduct $supplierProduct */
        $supplierProduct = $supplier->supplierProducts()->create($data);

        $supplierProduct->salesStats()->create([
                                                   'scope' => 'sales-tenant-currency'
                                               ]);


        $res->model    = $supplierProduct;
        $res->model_id = $supplierProduct->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
