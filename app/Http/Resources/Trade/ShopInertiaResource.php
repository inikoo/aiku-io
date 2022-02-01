<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 28 Jan 2022 16:52:27 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Resources\Trade;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;


class ShopInertiaResource extends JsonResource
{

    public function toArray($request): array|Arrayable|JsonSerializable
    {
        /** @var \App\Models\Trade\Shop $shop */
        $shop = $this;


        return [
            'id'       => $shop->id,
            'code'     => $shop->code,
            'name'     => $shop->name,
            'can_view' => $request->user()->hasPermissionTo("shops.view.$shop->id")

        ];
    }
}
