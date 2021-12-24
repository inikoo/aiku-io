<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 23 Dec 2021 02:23:22 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Helpers\Attachment\Traits;


use App\Models\Helpers\Attachment;
use App\Models\HumanResources\Employee;

trait HasValidateModelAttachment
{

    public function validateModelAttachment(Employee $model, Attachment $attachment): bool
    {
        return (
            $attachment->attachmentable_type == class_basename($model::class)
            and $attachment->attachmentable_id == $model->id
        );
    }



}

