<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 21 Dec 2021 16:32:00 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Helpers\AttachmentModel;

use App\Http\Resources\Helpers\AttachmentModelResource;
use App\Models\Helpers\AttachmentModel;
use App\Models\HumanResources\Employee;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\HttpFoundation\JsonResponse;

class ShowEmployeeAttachment
{
    use AsAction;

    public function handle(Employee $employee, AttachmentModel $attachmentModel): ?AttachmentModel
    {

        if($attachmentModel->attachmentable_type=='Employee' and $attachmentModel->attachmentable_id==$employee->id){
            return $attachmentModel;
        }
        return null;

    }

    public function jsonResponse(?AttachmentModel $attachmentModel): AttachmentModelResource|JsonResponse
    {
        if ($attachmentModel) {
            return new AttachmentModelResource($attachmentModel);
        }else{
            return response()->json(['error' => 'AttachmentModel do not belows to Model'], 400);

        }

    }
}
