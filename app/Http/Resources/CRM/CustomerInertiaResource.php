<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 28 Jan 2022 15:27:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Resources\CRM;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;


class CustomerInertiaResource extends JsonResource
{

    public function toArray($request): array|Arrayable|JsonSerializable
    {
        /** @var \App\Models\CRM\Customer $customer */
        $customer = $this;


        return [
            'id'              => $customer->id,
            'shop_id'         => $customer->shop_id,
            'shop_code'       => $this->whenLoaded('shop', $customer->shop->code),
            'customer_number' => sprintf('%05d', $customer->id),
            'name'            => $customer->name,
            'location'        => $customer->location

        ];
    }
}
