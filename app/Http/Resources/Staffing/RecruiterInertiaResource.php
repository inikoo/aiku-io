<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 07 Apr 2022 16:10:56 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Resources\Staffing;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;


/**
 * @property mixed $id
 * @property mixed $nickname
 * @property mixed $name
 */
class RecruiterInertiaResource extends JsonResource
{



    public function toArray($request): array|Arrayable|JsonSerializable
    {

        return [
            'id'            => $this->id,
            'nickname'      => $this->nickname,
            'name'          => $this->name
        ];
    }
}
