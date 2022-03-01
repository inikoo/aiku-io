<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 01 Mar 2022 02:13:39 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Hydrators;

use App\Models\CRM\FulfilmentCustomer;
use App\Models\Inventory\UniqueStock;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;


class HydrateFulfilmentCustomer extends HydrateModel
{

    public string $commandSignature = 'hydrate:fulfilment_customer {id} {--t|tenant=* : Tenant code}';


    public function handle(FulfilmentCustomer $fulfilmentCustomer): void
    {
        $this->uniqueStocks($fulfilmentCustomer);
    }

    public function uniqueStocks(FulfilmentCustomer $fulfilmentCustomer): void
    {
        $numberUniqueStocks = $fulfilmentCustomer->uniqueStocks->count();
        $stats = [
            'number_unique_stocks' => $numberUniqueStocks,
        ];



        $uniqueStockTypes = ['pallet', 'box', 'oversize','item'];
        $uniqueStockTypeCounts = UniqueStock::where('fulfilment_customer_id', $fulfilmentCustomer->id)
            ->selectRaw('type, count(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type')->all();


        foreach ($uniqueStockTypes as $uniqueStockType) {
            $stats['number_unique_stocks_type'.$uniqueStockType] = Arr::get($uniqueStockTypeCounts, $uniqueStockType, 0);
        }


        $uniqueStockStates = ['in-process', 'received', 'booked-in', 'booked-out', 'invoiced', 'lost'];
        $uniqueStockStateCounts = UniqueStock::where('fulfilment_customer_id', $fulfilmentCustomer->id)
            ->selectRaw('state, count(*) as total')
            ->groupBy('state')
            ->pluck('total', 'state')->all();


        foreach ($uniqueStockStates as $uniqueStockState) {
            $stats['number_unique_stocks_state_'.str_replace('-', '_', $uniqueStockState)] = Arr::get($uniqueStockStateCounts, $uniqueStockState, 0);
        }


        $fulfilmentCustomer->update($stats);
    }



    protected function getModel(int $id): FulfilmentCustomer
    {
        return FulfilmentCustomer::find($id);
    }

    protected function getAllModels(): Collection
    {
        return FulfilmentCustomer::withTrashed()->get();
    }

}


