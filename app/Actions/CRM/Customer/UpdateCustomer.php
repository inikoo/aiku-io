<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 14 Oct 2021 01:12:48 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\CRM\Customer;

use App\Actions\Migrations\MigrationResult;
use App\Models\CRM\Customer;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateCustomer
{
    use AsAction;

    public function handle(
        Customer $customer,
        array $contactData,
        array $customerData,
    ): MigrationResult {
        $res = new MigrationResult();

        $customer->contact()->update($contactData);
        $res->changes = array_merge($res->changes, $customer->contact->getChanges());

        $customer->update($customerData);
        $res->changes = array_merge($res->changes, $customer->getChanges());

        $res->model    = $customer;
        $res->model_id = $customer->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
