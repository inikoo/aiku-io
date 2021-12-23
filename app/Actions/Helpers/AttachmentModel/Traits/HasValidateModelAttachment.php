<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 23 Dec 2021 02:23:22 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Helpers\AttachmentModel\Traits;


use App\Http\Resources\Helpers\AttachmentModelResource;
use App\Models\Helpers\AttachmentModel;
use App\Models\HumanResources\Employee;
use Symfony\Component\HttpFoundation\JsonResponse;

trait HasValidateModelAttachment
{

    public function validateModelAttachmentModel(Employee $model, AttachmentModel $attachmentModel): bool
    {
        return (
            $attachmentModel->attachmentable_type == class_basename($model::class)
            and $attachmentModel->attachmentable_id == $model->id
        );
    }



}

