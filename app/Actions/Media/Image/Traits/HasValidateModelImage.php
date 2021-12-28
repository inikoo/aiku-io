<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 28 Dec 2021 20:45:02 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Media\Image\Traits;


use App\Models\HumanResources\Employee;
use App\Models\Media\Image;

use function class_basename;

trait HasValidateModelImage
{

    public function validateModelImage(Employee $model, Image $image): bool
    {
        return (
            $image->imageable_type == class_basename($model::class)
            and $image->imageable_id == $model->id
        );
    }



}

