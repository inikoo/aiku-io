<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 30 Jan 2022 23:38:05 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Hydrators;

use App\Models\Inventory\Warehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;


class HydrateWarehouse extends HydrateModel
{

    public string $commandSignature = 'hydrate:warehouse {id} {--t|tenant=* : Tenant nickname}';


    public function handle(?Model $model): void
    {
        if (!$model) {
            return;
        }
        /** @var Warehouse $warehouse */
        $warehouse=$model;

        $warehouse->stats->update(
            [
                'number_locations'=>$warehouse->locations->count(),
                'number_warehouse_areas'=>$warehouse->warehouseAreas()->count()

            ]
        );
    }

    protected function getModel(int $id): Warehouse
    {
        return Warehouse::find($id);
    }

    protected function getAllModels(): Collection
    {
        return Warehouse::withTrashed()->get();
    }


}


