<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 14 Oct 2021 01:12:48 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\CRM\Customer;

use App\Models\CRM\Customer;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateCustomer
{
    use AsAction;

    public function handle(
        Customer $customer,
        array $contactData,
        array $customerData,
    ): Customer {

        $customer->contact()->update($contactData);
        $customer->update($customerData);
        return $customer;
    }
}
