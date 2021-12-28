<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 28 Dec 2021 15:37:31 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Media\Image\Employee;

use App\Actions\Media\Image\Traits\HasModelImages;
use App\Models\HumanResources\Employee;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowEmployeeImages
{
    use AsAction;
    use HasModelImages;

    public function handle(Employee $employee): Employee
    {
        return $employee;
    }




}
