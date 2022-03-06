<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 31 Jan 2022 03:48:06 Malaysia Time, Kuala Lumpur, Malaysia
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
 * @property string $name
 * @property int $warehouse_id
 * @property int $number_locations
 *
 */
class WarehouseAreaInertiaResource extends JsonResource
{

    public function toArray($request): array|Arrayable|JsonSerializable
    {
        return [
            'id'               => $this->id,
            'code'             => $this->code,
            'name'             => $this->name,
            'warehouse_id'     => $this->warehouse_id,
            'number_locations' => $this->number_locations,

        ];
    }
}
