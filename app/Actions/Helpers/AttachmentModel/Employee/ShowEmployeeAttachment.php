<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 23 Dec 2021 00:41:27 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Helpers\AttachmentModel\Employee;

use App\Actions\Helpers\AttachmentModel\Traits\HasModelAttachment;
use App\Models\Helpers\AttachmentModel;
use App\Models\HumanResources\Employee;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowEmployeeAttachment
{
    use AsAction;
    use HasModelAttachment;

    public function handle(Employee $employee, AttachmentModel $attachmentModel): ?AttachmentModel
    {
        return $this->handleModelAware($employee,$attachmentModel);
    }


}
