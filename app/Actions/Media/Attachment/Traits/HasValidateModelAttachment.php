<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 28 Dec 2021 15:22:55 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Media\Attachment\Traits;


use App\Models\Helpers\Attachment;
use App\Models\HumanResources\Employee;

use function class_basename;

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

