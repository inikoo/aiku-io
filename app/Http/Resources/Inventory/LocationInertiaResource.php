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


/**
 * @property int $id
 * @property string $code
 * @property int $warehouse_id
 * @property int warehouse_area_id
 * @property string warehouse_area_code
 * @property string $warehouse_code
 */
class LocationInertiaResource extends JsonResource
{

    public function toArray($request): array|Arrayable|JsonSerializable
    {
        return [
            'id'                  => $this->id,
            'code'                => $this->code,
            'warehouse_id'        => $this->warehouse_id,
            'warehouse_area_id'   => $this->warehouse_area_id,
            'warehouse_area_code' => $this->warehouse_area_code,
            'warehouse_code'      => $this->warehouse_code,

        ];
    }
}
