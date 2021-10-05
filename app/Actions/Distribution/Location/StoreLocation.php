<?php

namespace App\Actions\Distribution\Location;

use App\Models\Distribution\Warehouse;
use App\Models\Distribution\WarehouseArea;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreLocation
{
    use AsAction;

    public function handle(WarehouseArea|Warehouse $parent, array $data): Model
    {
        if(class_basename($parent::class)=='WarehouseArea'){
            $data['warehouse_id']=$parent->warehouse_id;
        }
        return $parent->locations()->create($data);
    }
}
