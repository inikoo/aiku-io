<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 15 Apr 2022 00:16:46 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Resources\Marketing;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;


/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $shop_id
 * @property int $department_id
 * @property int $family_id
 */
class ProductInertiaResource extends JsonResource
{

    public function toArray($request): array|Arrayable|JsonSerializable
    {
        return [
            'id'            => $this->id,
            'code'          => $this->code,
            'name'          => $this->name,
            'shop_id'       => $this->shop_id,
            'department_id' => $this->department_id,
            'family_id'     => $this->family_id,

        ];
    }
}
