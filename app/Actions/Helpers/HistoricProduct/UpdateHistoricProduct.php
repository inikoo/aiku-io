<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 29 Sep 2021 16:47:46 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Helpers\HistoricProduct;

use App\Models\Helpers\HistoricProduct;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateHistoricProduct
{
    use AsAction;

    public function handle(HistoricProduct $historicProduct, array $data): HistoricProduct
    {
        $historicProduct->update($data);
        return $historicProduct;
    }
}
