<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 01 Dec 2021 19:52:11 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Accounting\InvoiceTransaction;

use App\Actions\Migrations\MigrationResult;
use App\Models\Sales\Order;
use App\Models\Sales\Transaction;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreInvoiceTransaction
{
    use AsAction;

    public function handle(Transaction|Order $parent, array $data): MigrationResult
    {
        $res = new MigrationResult();

        $data['shop_id']=$parent->shop_id;
        $data['customer_id']=$parent->customer_id;

        /** @var \App\Models\Accounting\InvoiceTransaction $invoiceTransaction */
        $invoiceTransaction = $parent->invoiceTransactions()->create($data);

        $res->model    = $invoiceTransaction;
        $res->model_id = $invoiceTransaction->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
