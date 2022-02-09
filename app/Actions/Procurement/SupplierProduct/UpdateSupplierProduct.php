<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 09 Feb 2022 04:52:50 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\SupplierProduct;

use App\Models\Procurement\SupplierProduct;
use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateSupplierProduct
{
    use AsAction;
    use WithUpdate;

    public function handle(SupplierProduct $supplierProduct, array $modelData): ActionResult
    {
        $res = new ActionResult();

        $supplierProduct->update(Arr::except($modelData, ['data', 'settings']));
        $supplierProduct->update($this->extractJson($modelData, ['data', 'settings']));

        $res->changes = array_merge($res->changes, $supplierProduct->getChanges());

        $res->model    = $supplierProduct;
        $res->model_id = $supplierProduct->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
