<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 28 Dec 2021 15:22:55 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Media\Attachment\Employee;

use App\Actions\Media\Attachment\Traits\HasModelAttachment;
use App\Models\Helpers\Attachment;
use App\Models\HumanResources\Employee;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowEmployeeAttachment
{
    use AsAction;
    use HasModelAttachment;

    public function handle(Employee $employee, Attachment $attachment): ?Attachment
    {
        return $this->handleModelAware($employee,$attachment);
    }


}
