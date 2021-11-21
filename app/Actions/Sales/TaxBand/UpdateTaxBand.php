<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 12 Nov 2021 17:21:04 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Sales\TaxBand;

use App\Actions\Migrations\MigrationResult;
use App\Actions\WithUpdate;
use App\Models\Sales\TaxBand;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateTaxBand
{
    use AsAction;
    use WithUpdate;

    public function handle(TaxBand $taxBand, array $modelData): MigrationResult
    {
        $res = new MigrationResult();

        $taxBand->update( Arr::except($modelData, ['data']));
        $taxBand->update($this->extractJson($modelData));

        $res->changes = array_merge($res->changes, $taxBand->getChanges());

        $res->model    = $taxBand;
        $res->model_id = $taxBand->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
