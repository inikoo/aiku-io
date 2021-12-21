<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 21 Dec 2021 01:14:01 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\Employee;

use App\Http\Resources\Helpers\AttachmentModelResource;
use App\Http\Resources\Helpers\ModelAttachmentModelResource;
use App\Models\Helpers\AttachmentModel;
use App\Models\HumanResources\Employee;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\QueryBuilder\QueryBuilder;

class ShowEmployeeAttachments
{
    use AsAction;

    public function handle(Employee $employee): Employee
    {
        return $employee;
    }


    public function jsonResponse(Employee $employee): AnonymousResourceCollection
    {
        $attachmentModels = QueryBuilder::for(AttachmentModel::class)
            ->where('attachmentable_type', class_basename($employee))
            ->where('attachmentable_id', $employee->id)
            ->paginate();

        return ModelAttachmentModelResource::collection($attachmentModels);
    }


}
