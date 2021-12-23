<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 21 Dec 2021 00:29:35 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Resources\Helpers;

use App\Http\Resources\Traits\WhenMorphToLoaded;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class AttachmentModelResource extends JsonResource
{
    use WhenMorphToLoaded;

    public function toArray($request): array|Arrayable|JsonSerializable
    {
        /** @var \App\Models\Helpers\AttachmentModel $attachmentModel */
        $attachmentModel = $this;


        return [
            'id'                  => $attachmentModel->id,
            'attachmentable_type' => $attachmentModel->attachmentable_type,
            'attachmentable_id'   => $attachmentModel->attachmentable_id,
            'scope'               => $attachmentModel->scope,
            'caption'             => $attachmentModel->caption,
            'public'              => $attachmentModel->public,
            'filename'            => $attachmentModel->filename,
            'created_at'          => $attachmentModel->created_at,
            'updated_at'          => $attachmentModel->updated_at,

        ];
    }
}
