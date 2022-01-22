<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 22 Jan 2022 14:43:27 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Resources\System;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;


class UserInertiaResource extends JsonResource
{

    public function toArray($request): array|Arrayable|JsonSerializable
    {
        /** @var \App\Models\System\User $user */
        $user = $this;

        return [
            'id'            => $user->id,
            'status'        => $user->status,
            'username'      => $user->username,
            'userable_type' => $user->localised_userable_type,
            'name'          => $user->name,


            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    }
}
