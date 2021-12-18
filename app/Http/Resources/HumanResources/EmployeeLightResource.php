<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 17 Dec 2021 14:59:47 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Resources\HumanResources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;


class EmployeeLightResource extends JsonResource
{

    public function toArray($request): array|Arrayable|JsonSerializable
    {
        /** @var \App\Models\HumanResources\Employee $employee */
        $employee = $this;



        return [
            'id'                  => $employee->id,
            'nickname'            => $employee->nickname,
            'worker_number'       => $employee->worker_number,
        ];
    }
}
