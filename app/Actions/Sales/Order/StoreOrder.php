<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 25 Nov 2021 16:15:50 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Sales\Order;

use App\Actions\Helpers\Address\StoreImmutableAddress;
use App\Models\Utils\ActionResult;
use App\Models\CRM\Customer;
use App\Models\Helpers\Address;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreOrder
{
    use AsAction;

    public function handle(
        Customer $customer,
        array $modelData,
        Address $billingAddress,
        Address $deliveryAddress

    ): ActionResult
    {
        $res = new ActionResult();



        if($customer->vendor_type=='Customer'){
            $modelData['customer_id']=$customer->vendor_id;
            $modelData['customer_client_id']=$customer->id;

        }else{
            $modelData['customer_id']=$customer->id;

        }

        $modelData['currency_id']=$customer->shop->currency_id;
        $modelData['shop_id']=$customer->shop_id;

        $billingAddress=StoreImmutableAddress::run($billingAddress);
        $deliveryAddress=StoreImmutableAddress::run($deliveryAddress);

        $modelData['delivery_address_id']=$deliveryAddress->id;
        $modelData['billing_address_id']=$billingAddress->id;


        /** @var \App\Models\Sales\Order $order */
        $order = $customer->shop->orders()->create($modelData);


        $res->model    = $order;
        $res->model_id = $order->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
