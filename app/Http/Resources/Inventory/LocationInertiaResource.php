<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 29 Jan 2022 02:06:21 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Resources\Inventory;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;


class LocationInertiaResource extends JsonResource
{

    public function toArray($request): array|Arrayable|JsonSerializable
    {
        /** @var \App\Models\Inventory\Location $location */
        $location = $this;


        return [
            'id'                  => $location->id,
            'code'                => $location->code,
            'warehouse_id'        => $location->warehouse_id,
            'warehouse_area_id'   => $location->warehouse_area_id,
            'warehouse_area_code' => $location->warehouse_area_code,

        ];
    }
}
