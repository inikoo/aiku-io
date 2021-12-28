<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 28 Dec 2021 14:17:36 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Resources\Media;

use App\Http\Resources\Traits\WhenMorphToLoaded;
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
            'id'                  => $attachment->id,
            'attachmentable_type' => $attachment->attachmentable_type,
            'attachmentable_id'   => $attachment->attachmentable_id,
            'scope'               => $attachment->scope,
            'caption'             => $attachment->caption,
            'public'              => $attachment->public,
            'filename'            => $attachment->filename,
            'created_at'          => $attachment->created_at,
            'updated_at'          => $attachment->updated_at,

        ];
    }
}
