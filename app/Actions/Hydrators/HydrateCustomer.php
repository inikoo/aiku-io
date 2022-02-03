<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 03 Feb 2022 01:40:54 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Hydrators;

use App\Models\CRM\Customer;
use Illuminate\Support\Collection;


class HydrateCustomer extends HydrateModel
{

    public string $commandSignature = 'hydrate:customer {id} {--t|tenant=* : Tenant nickname}';


    public function handle(Customer $customer): void
    {
        $this->contact($customer);
    }

    public function contact(Customer $customer): void
    {
        $customer->update(
            [

                'location' => $customer->billingAddress->getLocation()

            ]
        );
    }

    protected function getModel(int $id): Customer
    {
        return Customer::find($id);
    }

    protected function getAllModels(): Collection
    {
        return Customer::withTrashed()->get();
    }

}


