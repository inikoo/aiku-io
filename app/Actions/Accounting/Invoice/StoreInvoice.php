<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 01 Dec 2021 16:27:29 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Accounting\Invoice;

use App\Actions\Helpers\Address\StoreImmutableAddress;
use App\Models\Utils\ActionResult;
use App\Models\Helpers\Address;
use App\Models\Sales\Order;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreInvoice
{
    use AsAction;

    public function handle(
        Order $order,
        Address $billingAddress,
        array $modelData

    ): ActionResult
    {
        $res = new ActionResult();

        $modelData['shop_id']=$order->shop_id;
        $modelData['customer_id']=$order->customer_id;
        $modelData['order_id']=$order->id;
        $modelData['currency_id']=$order->currency_id;

        $billingAddress=StoreImmutableAddress::run($billingAddress);
        $modelData['billing_address_id']=$billingAddress->id;

        /** @var \App\Models\Accounting\Invoice $invoice */

        $invoice = $order->invoices()->create($modelData);


        $res->model    = $invoice;
        $res->model_id = $invoice->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
