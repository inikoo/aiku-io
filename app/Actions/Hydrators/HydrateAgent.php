<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 02 Feb 2022 02:50:44 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Hydrators;

use App\Models\Procurement\Agent;
use App\Models\Procurement\PurchaseOrder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;


class HydrateAgent extends HydrateModel
{

    public string $commandSignature = 'hydrate:agent {id} {--t|tenant=* : Tenant code}';


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

        $stats = [
            'number_suppliers'       => $agent->suppliers->count(),
            'number_purchase_orders' => $agent->purchaseOrders()->count()
        ];
        $purchaseOrderStates = ['in-process', 'submitted',  'confirmed', 'dispatched', 'delivered','cancelled'];
        $purchaseOrderStateCount = PurchaseOrder::selectRaw('state, count(*) as total')
            ->where('vendor_type','Agent')
            ->where('vendor_id',$agent->id)

            ->groupBy('state')
            ->pluck('total', 'state')->all();

        foreach ($purchaseOrderStates as $purchaseOrderState) {
            $stats['number_purchase_orders_state_'.str_replace('-', '_',$purchaseOrderState)] = Arr::get($purchaseOrderStateCount, $purchaseOrderState, 0);
        }

        $agent->stats->update($stats);



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


