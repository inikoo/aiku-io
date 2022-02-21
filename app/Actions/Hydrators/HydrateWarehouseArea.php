<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 30 Jan 2022 23:49:15 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Hydrators;

use App\Models\Inventory\WarehouseArea;
use Illuminate\Support\Collection;


class HydrateWarehouseArea extends HydrateModel
{

    public string $commandSignature = 'hydrate:warehouse_area {id} {--t|tenant=* : Tenant code}';


    public function handle(?WarehouseArea $warehouseArea): void
    {
        if (!$warehouseArea) {
            return;
        }

        $warehouseArea->stats->update(
            [
                'number_locations' => $warehouseArea->locations->count(),

            ]
        );
    }

    protected function getModel(int $id): WarehouseArea
    {
        return WarehouseArea::find($id);
    }

    protected function getAllModels(): Collection
    {
        return WarehouseArea::withTrashed()->get();
    }


}


