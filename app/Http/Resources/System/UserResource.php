<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 20 Oct 2021 16:31:19 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Resources\System;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;


class UserResource extends JsonResource
{

    public function toArray($request): array|Arrayable|JsonSerializable
    {
        /** @var \App\Models\System\User $user */
        $user = $this;

        return [
            'id'                 => $user->id,
            'status'             => $user->status,
            'username'           => $user->username,
            'nickname'           => $user->userable->nickname,
            'roles'              => $user->getRoleNames(),
            'direct-permissions' => $user->getDirectPermissions(),
            'permissions'        => $user->getAllPermissions()->pluck('name'),


            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    }
}
