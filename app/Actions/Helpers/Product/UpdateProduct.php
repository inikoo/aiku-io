<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 29 Sep 2021 16:47:46 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Helpers\Product;

use App\Actions\Migrations\MigrationResult;
use App\Models\Helpers\Product;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateProduct
{
    use AsAction;

    public function handle(Product $product, array $data): MigrationResult
    {
        $res = new MigrationResult();

        $product->update($data);
        $res->changes = array_merge($res->changes, $product->getChanges());

        $res->model    = $product;
        $res->model_id = $product->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
