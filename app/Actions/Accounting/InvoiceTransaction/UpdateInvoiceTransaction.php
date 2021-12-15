<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 01 Dec 2021 21:57:37 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Accounting\InvoiceTransaction;

use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use App\Models\Accounting\InvoiceTransaction;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateInvoiceTransaction
{
    use AsAction;
    use WithUpdate;

    public function handle(InvoiceTransaction $invoiceTransaction, array $modelData): ActionResult
    {
        $res = new ActionResult();

        $invoiceTransaction->update( Arr::except($modelData, ['data']));
        $invoiceTransaction->update($this->extractJson($modelData));

        $res->changes = array_merge($res->changes, $invoiceTransaction->getChanges());

        $res->model    = $invoiceTransaction;
        $res->model_id = $invoiceTransaction->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
