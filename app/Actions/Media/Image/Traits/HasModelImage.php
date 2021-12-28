<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 28 Dec 2021 17:48:54 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Media\Image\Traits;


use App\Http\Resources\Media\ImageResource;
use App\Models\HumanResources\Employee;
use App\Models\Media\Image;
use Symfony\Component\HttpFoundation\JsonResponse;

use function class_basename;
use function response;

trait HasModelImage
{

    public function jsonResponse(?Image $image): ImageResource|JsonResponse
    {
        if ($image) {
            return new ImageResource($image);
        } else {
            return response()->json(['error' => 'Image do not belongs to Model'], 400);
        }
    }

    public function handleModelAware(Employee $model, Image $image): ?Image
    {
        if ($image->imageable_type == class_basename($model::class) and $image->imageable_id == $model->id) {
            return $image;
        }

        return null;
    }






}

