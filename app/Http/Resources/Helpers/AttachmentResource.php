<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 21 Oct 2021 12:37:51 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Resources\Helpers;

use App\Http\Resources\HumanResources\EmployeeLightResource;
use App\Http\Resources\Traits\WhenMorphToLoaded;
use App\Models\HumanResources\Employee;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class AttachmentResource extends JsonResource
{
    use WhenMorphToLoaded;

    public function toArray($request): array|Arrayable|JsonSerializable
    {
        /** @var \App\Models\Helpers\Attachment $attachment */
        $attachment = $this;


        return [
            'id'         => $attachment->id,
            'mime'       => $attachment->mime,
            'filesize'   => $attachment->formatted_filesize,
            'models'     => AttachmentModelResource::collection($this->whenLoaded('models')),
            'created_at' => $attachment->created_at,
            'updated_at' => $attachment->updated_at,

        ];
    }
}
