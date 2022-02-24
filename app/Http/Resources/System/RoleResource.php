<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 20 Oct 2021 21:40:44 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Resources\System;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;


class RoleResource extends JsonResource
{

    public function toArray($request): array|Arrayable|JsonSerializable
    {
        /** @var \App\Models\Auth\Role $role */
        $role = $this;

        return [
            'id'          => $role->id,
            'name'        => $role->name,
            'guard_name'  => $role->guard_name,
            'permissions' => $role->permissions->pluck('name'),
            'users'       => $role->users->pluck('username'),
            'created_at'  => $role->created_at,
            'updated_at'  => $role->updated_at,
        ];
    }
}
