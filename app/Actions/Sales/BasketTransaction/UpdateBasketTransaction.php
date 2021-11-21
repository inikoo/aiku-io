<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 17 Nov 2021 13:52:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Sales\BasketTransaction;

use App\Actions\Migrations\MigrationResult;
use App\Actions\WithUpdate;
use App\Models\Sales\BasketTransaction;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateBasketTransaction
{
    use AsAction;
    use WithUpdate;

    public function handle(BasketTransaction $transaction, array $modelData): MigrationResult
    {
        $res = new MigrationResult();

        $transaction->update( Arr::except($modelData, ['data']));
        $transaction->update($this->extractJson($modelData));

        $res->changes = array_merge($res->changes, $transaction->getChanges());

        $res->model    = $transaction;
        $res->model_id = $transaction->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
