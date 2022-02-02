<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 02 Feb 2022 03:17:39 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Resources\Procurement;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;


class AgentInertiaResource extends JsonResource
{

    public function toArray($request): array|Arrayable|JsonSerializable
    {


        return [
            'id'       => $this->id,
            'code'     => $this->code,
            'name'     => $this->name,
            'number_suppliers'=>$this->number_suppliers,
            'number_purchase_orders'=>$this->number_purchase_orders

        ];
    }
}
