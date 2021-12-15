<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 25 Nov 2021 21:51:28 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Sales\Transaction;

use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use App\Models\Sales\Transaction;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateTransaction
{
    use AsAction;
    use WithUpdate;

    public function handle(Transaction $transaction, array $modelData): ActionResult
    {
        $res = new ActionResult();

        $transaction->update( Arr::except($modelData, ['data']));
        $transaction->update($this->extractJson($modelData));

        $res->changes = array_merge($res->changes, $transaction->getChanges());

        $res->model    = $transaction;
        $res->model_id = $transaction->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
