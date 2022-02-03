<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 03 Feb 2022 18:13:14 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Resources\Inventory;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;


class StockInertiaResource extends JsonResource
{

    public function toArray($request): array|Arrayable|JsonSerializable
    {
        return [
            'id'          => $this->id,
            'code'        => $this->code,
            'description' => $this->description,
        ];
    }
}
