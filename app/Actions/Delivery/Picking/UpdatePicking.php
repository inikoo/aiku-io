<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 09 Dec 2021 14:46:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Delivery\Picking;

use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use App\Models\Financials\InvoiceTransaction;
use App\Models\Delivery\Picking;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdatePicking
{
    use AsAction;
    use WithUpdate;

    public function handle(Picking $picking, array $modelData): ActionResult
    {
        $res = new ActionResult();

        $picking->update( Arr::except($modelData, ['data']));
        $picking->update($this->extractJson($modelData));

        $res->changes = array_merge($res->changes, $picking->getChanges());

        $res->model    = $picking;
        $res->model_id = $picking->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';
        return $res;
    }
}
