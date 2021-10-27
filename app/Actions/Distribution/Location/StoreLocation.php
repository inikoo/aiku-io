<?php

namespace App\Actions\Distribution\Location;

use App\Actions\Migrations\MigrationResult;
use App\Models\Distribution\Warehouse;
use App\Models\Distribution\WarehouseArea;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreLocation
{
    use AsAction;

    public function handle(WarehouseArea|Warehouse $parent, array $data): MigrationResult
    {
        $res = new MigrationResult();


        if (class_basename($parent::class) == 'WarehouseArea') {
            $data['warehouse_id'] = $parent->warehouse_id;
        }
        /** @var \App\Models\Distribution\Location $location */
        $location = $parent->locations()->create($data);

        $res->model    = $location;
        $res->model_id = $location->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
