<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 28 Dec 2021 15:22:55 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Media\Image\Employee;

use App\Actions\Media\Image\Traits\HasModelImage;
use App\Models\HumanResources\Employee;
use App\Models\Media\Image;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowEmployeeImage
{
    use AsAction;
    use HasModelImage;

    public function handle(Employee $employee, Image $image): ?Image
    {
        return $this->handleModelAware($employee, $image);
    }


}
