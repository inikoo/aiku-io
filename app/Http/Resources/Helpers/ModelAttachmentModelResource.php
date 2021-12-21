<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 21 Dec 2021 15:55:13 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Resources\Helpers;

use App\Http\Resources\Traits\WhenMorphToLoaded;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class ModelAttachmentModelResource extends JsonResource
{
    use WhenMorphToLoaded;

    public function toArray($request): array|Arrayable|JsonSerializable
    {
        /** @var \App\Models\Helpers\AttachmentModel $attachmentModel */
        $attachmentModel = $this;


        return [
            'id'                  => $attachmentModel->id,
            'scope'               => $attachmentModel->scope,
            'filename'            => $attachmentModel->filename,
            'created_at'          => $attachmentModel->created_at,
            'updated_at'          => $attachmentModel->updated_at,

        ];
    }
}
