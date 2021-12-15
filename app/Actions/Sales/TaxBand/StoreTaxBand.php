<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 12 Nov 2021 16:52:16 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Sales\TaxBand;

use App\Models\Utils\ActionResult;
use App\Models\Sales\TaxBand;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreTaxBand
{
    use AsAction;


    public function handle( array $taxBandData): ActionResult
    {
        $res  = new ActionResult();

        /** @var \App\Models\Sales\TaxBand $taxband */
        $taxBand= TaxBand::create($taxBandData);
        $res->model    = $taxBand;
        $res->model_id = $taxBand->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
