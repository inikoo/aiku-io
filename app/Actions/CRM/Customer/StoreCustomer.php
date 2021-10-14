<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 13 Oct 2021 20:00:51 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\CRM\Customer;

use App\Actions\Helpers\Address\StoreAddress;
use App\Models\CRM\Customer;
use App\Models\Selling\Shop;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreCustomer
{
    use AsAction;

    public function handle(
        Shop|Customer $vendor,
        array $customerData,
        array $contactData,
        array $customerAddressesData = []
    ): Customer {
        /** @var Customer $customer */

        $customer = $vendor->customers()->create($customerData);
        $customer->contact()->create($contactData);
        $addresses = [];

        $billing_address_id=null;
        $delivery_address_id=null;

        foreach ($customerAddressesData as $scope => $addressesData) {
            foreach ($addressesData as $addressData) {
                $address                 = StoreAddress::run($addressData);
                $addresses[$address->id] = ['scope' => $scope];
                if($scope=='billing'){
                    $billing_address_id=$address->id;
                }elseif($scope=='delivery'){
                    $delivery_address_id=$address->id;
                }
            }
        }

        if (class_basename($vendor::class) == 'Shop'   ) {
            if(!$delivery_address_id and $vendor->type!='dropshipping'){
                $delivery_address_id=$billing_address_id;
            }
        }


        $customer->addresses()->sync($addresses);
        $customer->billing_address_id=$billing_address_id;
        $customer->delivery_address_id=$delivery_address_id;
        $customer->save();
        return $customer;
    }
}
