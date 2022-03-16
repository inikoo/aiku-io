<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 17 Mar 2022 01:46:46 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Hydrators;

use App\Models\Inventory\Stock;
use Illuminate\Support\Collection;


class HydrateStock extends HydrateModel
{

    public string $commandSignature = 'hydrate:stock {id} {--t|tenant=* : Tenant code}';


    public function handle(Stock $stock): void
    {
        $this->stocks($stock);
    }

    public function stocks(Stock $stock): void
    {
        $numberLocations = $stock->locations->count();
        $stats = [
            'number_locations' => $numberLocations
        ];

        $stock->stats->update($stats);
    }



    protected function getModel(int $id): Stock
    {
        return Stock::find($id);
    }

    protected function getAllModels(): Collection
    {
        return Stock::withTrashed()->get();
    }

}


