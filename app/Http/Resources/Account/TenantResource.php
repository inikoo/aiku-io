<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 21 Oct 2021 12:37:45 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Resources\Account;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;


class TenantResource extends JsonResource
{

    public function toArray($request): array|Arrayable|JsonSerializable
    {
        /** @var \App\Models\Account\Tenant $tenant */
        $tenant = $this;

        return [
            'id'            => $tenant->id,
            'nickname'      => $tenant->nickname,
            'email'         => $tenant->email,
            'business_type' => $tenant->businessType->only(['slug','name']),

            'created_at' => $tenant->created_at,
            'updated_at' => $tenant->updated_at,
        ];
    }
}
