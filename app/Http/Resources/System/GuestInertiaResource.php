<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 27 Jan 2022 21:54:29 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Resources\System;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;


class GuestInertiaResource extends JsonResource
{

    public function toArray($request): array|Arrayable|JsonSerializable
    {
        /** @var \App\Models\HumanResources\Guest $guest */
        $guest = $this;


        return [
            'id'       => $guest->id,
            'nickname' => $guest->nickname,
            'status'   => $guest->status,
            'name'     => $guest->name
        ];
    }
}
