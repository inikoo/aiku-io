<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 28 Dec 2021 14:21:00 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Resources\Media;

use App\Http\Resources\Traits\WhenMorphToLoaded;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class ModelImageResource extends JsonResource
{
    use WhenMorphToLoaded;

    public function toArray($request): array|Arrayable|JsonSerializable
    {
        /** @var \App\Models\Media\Image $image */
        $image = $this;


        return [
            'id'         => $image->id,
            'scope'      => $image->scope,
            'caption'    => $image->caption,
            'public'     => $image->public,
            'created_at' => $image->created_at,
            'updated_at' => $image->updated_at,

        ];
    }
}
