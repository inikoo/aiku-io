<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 21 Oct 2021 12:37:51 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Resources\Helpers;

use App\Http\Resources\Traits\WhenMorphToLoaded;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class CommonAttachmentResource extends JsonResource
{
    use WhenMorphToLoaded;

    public function toArray($request): array|Arrayable|JsonSerializable
    {
        /** @var \App\Models\Helpers\CommonAttachment $attachment */
        $attachment = $this;


        return [
            'id'         => $attachment->id,
            'mime'       => $attachment->mime,
            'filesize'   => $attachment->formatted_filesize,
            'models'     => AttachmentResource::collection($this->whenLoaded('models')),
            'created_at' => $attachment->created_at,
            'updated_at' => $attachment->updated_at,

        ];
    }
}
