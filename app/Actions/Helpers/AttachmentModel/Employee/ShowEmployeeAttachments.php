<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 23 Dec 2021 00:41:32 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Helpers\AttachmentModel\Employee;

use App\Actions\Helpers\AttachmentModel\Traits\HasModelAttachments;
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
