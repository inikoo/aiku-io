<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 11 Oct 2021 15:13:51 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\Agent;

use App\Actions\Helpers\Address\StoreAddress;
use App\Models\Utils\ActionResult;
use App\Models\Account\Tenant;
use App\Models\Aiku\Aiku;
use App\Models\Procurement\Agent;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreAgent
{
    use AsAction;

    public function handle(Tenant|Aiku $parent, array $data, array $addressData): ActionResult
    {
        $res = new ActionResult();

        /** @var Agent $agent */
        $agent = $parent->agents()->create($data);
        $agent->stats()->create();


        $addresses               = [];
        $address                 = StoreAddress::run($addressData);
        $addresses[$address->id] = ['scope' => 'contact'];
        $agent->addresses()->sync($addresses);
        $agent->address_id = $address->id;
        $agent->location=$agent->getLocation();
        $agent->save();

        $res->model    = $agent;
        $res->model_id = $agent->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
