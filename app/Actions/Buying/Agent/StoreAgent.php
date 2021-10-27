<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 11 Oct 2021 15:13:51 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Buying\Agent;

use App\Actions\Helpers\Address\StoreAddress;
use App\Actions\Migrations\MigrationResult;
use App\Models\Account\Tenant;
use App\Models\Aiku\Aiku;
use App\Models\Buying\Agent;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreAgent
{
    use AsAction;

    public function handle(Tenant|Aiku $parent,  array $data, array $addressData, array $contactData): MigrationResult
    {
        $res  = new MigrationResult();

        /** @var Agent $agent */
        $agent                   = $parent->agents()->create($data);
        $agent->contact()->create($contactData);

        $addresses               = [];
        $address                 = StoreAddress::run($addressData);
        $addresses[$address->id] = ['scope' => 'contact'];
        $agent->addresses()->sync($addresses);
        $agent->contact->address_id = $address->id;
        $agent->contact->save();
        $agent->save();

        $res->model    = $agent;
        $res->model_id = $agent->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
