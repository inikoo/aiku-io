<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 28 Dec 2021 14:14:16 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Resources\Media;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;


class ImageResource extends JsonResource
{

    public function toArray($request): array|Arrayable|JsonSerializable
    {
        /** @var \App\Models\Media\Image $image */
        $image = $this;


        return [
            'id'             => $image->id,
            'imageable_type' => $image->imageable_type,
            'imageable_id'   => $image->imageable_id,
            'scope'          => $image->scope,
            'rank'           => $image->rank,
            'filename'       => $image->filename,
            'public'         => $image->public,
            'created_at'     => $image->created_at,
            'updated_at'     => $image->updated_at,
        ];
    }
}
