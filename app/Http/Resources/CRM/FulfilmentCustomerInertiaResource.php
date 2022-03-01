<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 01 Mar 2022 03:51:38 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Resources\CRM;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;


class FulfilmentCustomerInertiaResource extends JsonResource
{

    public function toArray($request): array|Arrayable|JsonSerializable
    {
        /** @var \App\Models\CRM\Customer $customer */
        $customer = $this;

        return [
            'id'                   => $customer->id,
            'shop_id'              => $customer->shop_id,
            'customer_number'      => sprintf('%05d', $customer->id),
            'name'                 => $customer->name,
            'location'             => $customer->location,
            'number_unique_stocks' => $customer->number_unique_stocks

        ];
    }
}
