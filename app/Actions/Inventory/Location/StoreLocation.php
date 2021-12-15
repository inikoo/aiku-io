<?php

namespace App\Actions\Inventory\Location;

use App\Models\Utils\ActionResult;
use App\Models\Inventory\Warehouse;
use App\Models\Inventory\WarehouseArea;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreLocation
{
    use AsAction;

    public function handle(WarehouseArea|Warehouse $parent, array $data): ActionResult
    {
        $res = new ActionResult();


        if (class_basename($parent::class) == 'WarehouseArea') {
            $data['warehouse_id'] = $parent->warehouse_id;
        }
        /** @var \App\Models\Inventory\Location $location */
        $location = $parent->locations()->create($data);

        $res->model    = $location;
        $res->model_id = $location->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
