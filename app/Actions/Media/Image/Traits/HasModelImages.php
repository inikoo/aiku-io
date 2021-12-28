<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 28 Dec 2021 15:41:15 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Media\Image\Traits;


use App\Http\Resources\Media\ModelAttachmentResource;
use App\Models\HumanResources\Employee;
use App\Models\Media\Image;
use App\Models\Trade\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\QueryBuilder;

use function class_basename;

trait HasModelImages
{

    public function jsonResponse(Employee|Product $model): AnonymousResourceCollection
    {
        $images = QueryBuilder::for(Image::class)
            ->where('imageable_type', class_basename($model))
            ->where('imageable_id', $model->id)
            ->paginate();

        return ModelAttachmentResource::collection($images);
    }

}

