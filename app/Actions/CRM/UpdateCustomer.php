<?php

namespace App\Actions\CRM;

use App\Models\CRM\Customer;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateCustomer
{
    use AsAction;

    public function handle(
        Customer $customer,
        array $customerData,
    ): Customer {
        $customer->update($customerData);


        return $customer;
    }
}
