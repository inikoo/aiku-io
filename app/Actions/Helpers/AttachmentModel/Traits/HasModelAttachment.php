<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 23 Dec 2021 00:32:12 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Helpers\AttachmentModel\Traits;


use App\Http\Resources\Helpers\AttachmentModelResource;
use App\Models\Helpers\AttachmentModel;
use App\Models\HumanResources\Employee;
use Symfony\Component\HttpFoundation\JsonResponse;

trait HasModelAttachment
{

    public function jsonResponse(?AttachmentModel $attachmentModel): AttachmentModelResource|JsonResponse
    {
        if ($attachmentModel) {
            return new AttachmentModelResource($attachmentModel);
        } else {
            return response()->json(['error' => 'AttachmentModel do not belongs to Model'], 400);
        }
    }

    public function handleModelAware(Employee $model, AttachmentModel $attachmentModel): ?AttachmentModel
    {
        if ($attachmentModel->attachmentable_type == class_basename($model::class) and $attachmentModel->attachmentable_id == $model->id) {
            return $attachmentModel;
        }

        return null;
    }


    public function validateModelAttachmentModel(string $className, int $modelID, AttachmentModel $attachmentModel): bool
    {

        //$attachmentModel=AttachmentModel::find($attachmentModelID);
        return (
            $attachmentModel->attachmentable_type == $className
            and $attachmentModel->attachmentable_id == $modelID
        );
    }




}

