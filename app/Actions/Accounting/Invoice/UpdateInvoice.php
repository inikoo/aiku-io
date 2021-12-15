<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 01 Dec 2021 16:31:54 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Accounting\Invoice;

use App\Actions\Helpers\Address\StoreImmutableAddress;
use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use App\Models\Accounting\Invoice;
use App\Models\Helpers\Address;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateInvoice
{
    use AsAction;
    use WithUpdate;

    public function handle(
        Invoice $invoice,
        Address $billingAddress,
        array $modelData,
    ): ActionResult
    {
        $res = new ActionResult();

        $billingAddress=StoreImmutableAddress::run($billingAddress);

        $modelData['billing_address_id']=$billingAddress->id;

        $invoice->update( Arr::except($modelData, ['data']));
        $invoice->update($this->extractJson($modelData));

        $res->changes = array_merge($res->changes, $invoice->getChanges());

        $res->model    = $invoice;
        $res->model_id = $invoice->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
