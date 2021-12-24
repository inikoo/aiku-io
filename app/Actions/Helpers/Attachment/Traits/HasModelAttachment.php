<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 23 Dec 2021 00:32:12 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Helpers\Attachment\Traits;


use App\Http\Resources\Helpers\AttachmentResource;
use App\Models\Helpers\Attachment;
use App\Models\HumanResources\Employee;
use Symfony\Component\HttpFoundation\JsonResponse;

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

