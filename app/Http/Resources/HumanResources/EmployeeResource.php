<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 21 Oct 2021 12:37:51 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Resources\HumanResources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;


class EmployeeResource extends JsonResource
{

    public function toArray($request): array|Arrayable|JsonSerializable
    {
        /** @var \App\Models\HumanResources\Employee $employee */
        $employee = $this;

        $contact            = $employee->contact->only('name', 'email', 'phone', 'date_of_birth');
        $contact['address'] = $employee->contact->data['address'] ?? null;


        return [
            'id'                  => $employee->id,
            'contact'             => $contact,
            'nickname'            => $employee->nickname,
            'worker_number'       => $employee->worker_number,
            'state'               => $employee->state,
            'employment_start_at' => $employee->employment_start_at,
            'employment_end_at'   => $employee->employment_end_at,
            'salary'              => $employee->salary,
            'user'                => $employee->user?->only('username', 'status'),
            'supervisors'         => EmployeeLightResource::collection($employee->supervisors),
            'team'                => EmployeeLightResource::collection($employee->team),

            'created_at' => $employee->created_at,
            'updated_at' => $employee->updated_at,
        ];
    }
}
