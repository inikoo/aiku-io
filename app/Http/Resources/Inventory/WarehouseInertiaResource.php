<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 28 Jan 2022 22:10:31 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Resources\Inventory;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;


class WarehouseInertiaResource extends JsonResource
{

    public function toArray($request): array|Arrayable|JsonSerializable
    {
        /** @var \App\Models\Inventory\Warehouse $warehouse */
        $warehouse = $this;


        return [
            'id'       => $warehouse->id,
            'code'     => $warehouse->code,
            'name'     => $warehouse->name,
            'can_view' => $request->user()->hasPermissionTo("warehouses.$warehouse->id.view")
        ];
    }
}
