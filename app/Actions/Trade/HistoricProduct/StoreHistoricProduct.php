<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 29 Sep 2021 16:47:56 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Trade\HistoricProduct;

use App\Actions\Migrations\MigrationResult;
use App\Models\Trade\Product;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreHistoricProduct
{
    use AsAction;

    public function handle(Product $product, array $data): MigrationResult
    {
        $res = new MigrationResult();
        /** @var \App\Models\Trade\HistoricProduct $historicProduct */

        $historicProduct = $product->historicRecords()->create($data);

        $res->model    = $historicProduct;
        $res->model_id = $historicProduct->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
