<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 29 Sep 2021 16:47:56 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Trade\Product;

use App\Models\Production\Workshop;
use App\Models\Utils\ActionResult;
use App\Models\Procurement\Supplier;
use App\Models\Trade\Shop;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreProduct
{
    use AsAction;

    public function handle(Shop|Supplier|Workshop $vendor, array $data): ActionResult
    {
        $res  = new ActionResult();
        /** @var \App\Models\Trade\Product $product */

        $product= $vendor->products()->create($data);

        $res->model    = $product;
        $res->model_id = $product->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';
        return $res;

    }
}
