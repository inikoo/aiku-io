<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 11 Oct 2021 15:13:46 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Buying\Supplier;

use App\Actions\Helpers\Address\StoreAddress;
use App\Models\Account\Tenant;
use App\Models\Aiku\Aiku;
use App\Models\Buying\Agent;
use App\Models\Buying\Supplier;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreSupplier
{
    use AsAction;

    public function handle(Tenant|Aiku|Agent $parent, array $data, array $addressData, array $contactData): Supplier
    {
        /** @var Supplier $supplier */
        $supplier = $parent->suppliers()->create($data);
        $supplier->contact()->create($contactData);
        $addresses               = [];
        $address                 = StoreAddress::run($addressData);
        $addresses[$address->id] = ['scope' => 'contact'];
        $supplier->addresses()->sync($addresses);
        $supplier->contact->address_id = $address->id;
        $supplier->contact->save();
        $supplier->save();

        return $supplier;
    }
}
