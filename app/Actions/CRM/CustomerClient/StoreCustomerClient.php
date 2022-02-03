<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 29 Jan 2022 00:55:05 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\CRM\CustomerClient;

use App\Actions\Helpers\Address\StoreAddress;
use App\Models\Utils\ActionResult;
use App\Models\CRM\Customer;
use App\Models\CRM\CustomerClient;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreCustomerClient
{
    use AsAction;

    public function handle(
        Customer $customer,
        array $customerClientData,
        array $customerClientAddressesData = []
    ): ActionResult {

        $res  = new ActionResult();

        $customerClientData['shop_id']=$customer->shop_id;

        /** @var CustomerClient $customerClient */
        $customerClient = $customer->clients()->create($customerClientData);
        $addresses = [];

        $delivery_address_id=null;

        foreach ($customerClientAddressesData as $addressesData) {
            foreach ($addressesData as $addressData) {
                $address                 = StoreAddress::run($addressData);
                $addresses[$address->id] = ['scope' => 'delivery'];

                    $delivery_address_id=$address->id;

            }
        }


        $customerClient->addresses()->sync($addresses);
        $customerClient->delivery_address_id=$delivery_address_id;
        $customerClient->save();

        $res->model    = $customerClient;
        $res->model_id = $customerClient->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
