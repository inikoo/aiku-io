<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 02 Feb 2022 03:32:14 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Resources\Procurement;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;


/**
 * @property mixed $location country/city
 * @property mixed $id
 * @property mixed $code
 * @property mixed $name
 * @property mixed $number_purchase_orders
 * @property mixed $owner_id
 * @property mixed $number_products
 */
class SupplierInertiaResource extends JsonResource
{

    public function toArray($request): array|Arrayable|JsonSerializable
    {
        return [
            'id'       => $this->id,
            'code'     => $this->code,
            'name'     => $this->name,
            'country'  => [$this->location[0], $this->location[1]],
            'location' => $this->location[2],

            'number_purchase_orders' => $this->number_purchase_orders,
            'number_products'        => $this->number_products,

            'owner_id' => $this->owner_id,

        ];
    }
}
