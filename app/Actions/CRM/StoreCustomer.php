<?php

namespace App\Actions\CRM;

use App\Actions\Helpers\Address\StoreAddress;
use App\Models\CRM\Customer;
use App\Models\Selling\Shop;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreCustomer
{
    use AsAction;

    public function handle(
        Shop $shop,
        array $customerData,
        array $customerAddressesData = []
    ): Customer {
        /** @var Customer $customer */
        $customer = $shop->customers()->create($customerData);

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

        if(!$delivery_address_id and $shop->type!='dropshipping'){
            $delivery_address_id=$billing_address_id;
        }

        $customer->addresses()->sync($addresses);
        $customer->billing_address_id=$billing_address_id;
        $customer->delivery_address_id=$delivery_address_id;
        $customer->save();

        return $customer;
    }
}
