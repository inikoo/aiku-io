<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 28 Dec 2021 15:22:55 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Media\Attachment\Employee;

use App\Actions\Media\Attachment\Traits\HasModelAttachments;
use App\Models\HumanResources\Employee;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowEmployeeAttachments
{
    use AsAction;
    use HasModelAttachments;

    public function handle(Employee $employee): Employee
    {
        return $employee;
    }




}
