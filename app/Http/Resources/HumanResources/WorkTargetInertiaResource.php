<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 07 Mar 2022 20:08:44 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Resources\HumanResources;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;


/**
 * @property int $id
 * @property mixed $date
 * @property int $employee_id
 * @property string employee_name
 * @property mixed $employee_nickname
 */
class WorkTargetInertiaResource extends JsonResource
{

    public function toArray($request): array|Arrayable|JsonSerializable
    {
        return [
            'id'            => $this->id,
            'formatted_id'  => Carbon::parse($this->date)->format('md').'-'.substr($this->employee_nickname,0,3),
            'date'          => $this->date,
            'employee_id'   => $this->employee_id,
            'employee_name' => $this->employee_name,


        ];
    }
}
