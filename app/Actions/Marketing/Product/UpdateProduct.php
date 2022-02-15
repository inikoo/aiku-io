<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 29 Sep 2021 16:47:46 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Marketing\Product;

use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use App\Models\Marketing\Product;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateProduct
{
    use AsAction;
    use WithUpdate;

    public function handle(Product $product, array $modelData): ActionResult
    {
        $res = new ActionResult();

        $product->update(Arr::except($modelData, ['data', 'settings']));
        $product->update($this->extractJson($modelData, ['data', 'settings']));

        $res->changes = array_merge($res->changes, $product->getChanges());

        $res->model    = $product;
        $res->model_id = $product->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
