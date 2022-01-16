<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 11 Oct 2021 15:13:46 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\Supplier;

use App\Actions\Helpers\Address\StoreAddress;
use App\Models\Utils\ActionResult;
use App\Models\Account\Tenant;
use App\Models\Aiku\Aiku;
use App\Models\Procurement\Agent;
use App\Models\Procurement\Supplier;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreSupplier
{
    use AsAction;

    public function handle(Tenant|Aiku|Agent $parent, array $data, array $addressData, array $contactData): ActionResult
    {
        $res  = new ActionResult();

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

        $res->model    = $supplier;
        $res->model_id = $supplier->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;    }
}
