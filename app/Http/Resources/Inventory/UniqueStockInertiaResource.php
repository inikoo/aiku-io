<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 01 Mar 2022 17:29:47 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Resources\Inventory;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;


/**
 * @property int $id
 * @property string $reference customer reference
 * @property string $customer_name
 * @property int $customer_id
 * @property int $shop_id
 */
class UniqueStockInertiaResource extends JsonResource
{

    public function toArray($request): array|Arrayable|JsonSerializable
    {
        return [
            'id'            => $this->id,
            'formatted_id'  => sprintf('%05d', $this->id),
            'reference'     => $this->reference,
            'customer_name' => $this->customer_name,
            'customer_id'   => $this->customer_id,
            'shop_id'       => $this->shop_id
        ];
    }
}
