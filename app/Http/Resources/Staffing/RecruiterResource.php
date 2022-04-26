<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 21 Oct 2021 12:37:51 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Resources\Staffing;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;


/**
 * @property int id
 * @property string nickname
 * @property string name
 * @property string created_at
 * @property string updated_at
 */
class RecruiterResource extends JsonResource
{

    public function toArray($request): array|Arrayable|JsonSerializable
    {

        return [
            'id'       => $this->id,
            'nickname' => $this->nickname,
            'name'     => $this->name,


            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
