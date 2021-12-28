<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 28 Dec 2021 15:22:55 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Media\Attachment\Traits;


use App\Http\Resources\Media\AttachmentResource;
use App\Models\Helpers\Attachment;
use App\Models\HumanResources\Employee;
use Symfony\Component\HttpFoundation\JsonResponse;

use function class_basename;
use function response;

trait HasModelAttachment
{

    public function jsonResponse(?Attachment $attachment): AttachmentResource|JsonResponse
    {
        if ($attachment) {
            return new AttachmentResource($attachment);
        } else {
            return response()->json(['error' => 'Attachment do not belongs to Model'], 400);
        }
    }

    public function handleModelAware(Employee $model, Attachment $attachment): ?Attachment
    {
        if ($attachment->attachmentable_type == class_basename($model::class) and $attachment->attachmentable_id == $model->id) {
            return $attachment;
        }

        return null;
    }






}

