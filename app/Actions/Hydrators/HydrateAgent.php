<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 02 Feb 2022 02:50:44 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Hydrators;

use App\Models\Procurement\Agent;
use Illuminate\Support\Collection;


class HydrateAgent extends HydrateModel
{

    public string $commandSignature = 'hydrate:agent {id} {--t|tenant=* : Tenant nickname}';


    public function handle(Agent $agent): void
    {
        $this->contact($agent);
        $this->stats($agent);
    }

    public function contact(Agent $agent)
    {
        $agent->update(
            [
                'location' => $agent->getLocation()
            ]
        );
    }

    public function stats(Agent $agent)
    {
        $agent->stats->update(
            [
                'number_suppliers'       => $agent->suppliers->count(),
                'number_purchase_orders' => $agent->purchaseOrders()->count()
            ]
        );
    }


    protected function getModel(int $id): Agent
    {
        return Agent::find($id);
    }

    protected function getAllModels(): Collection
    {
        return Agent::withTrashed()->get();
    }


}


