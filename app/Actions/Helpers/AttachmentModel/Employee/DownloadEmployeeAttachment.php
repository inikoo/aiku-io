<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 23 Dec 2021 00:41:18 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Helpers\AttachmentModel\Employee;

use App\Models\Helpers\AttachmentModel;
use App\Models\HumanResources\Employee;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadEmployeeAttachment
{
    use AsAction;

    public function handle(Employee $employee, AttachmentModel $attachmentModel): ?AttachmentModel
    {

        if($attachmentModel->attachmentable_type=='Employee' and $attachmentModel->attachmentable_id==$employee->id){
            return $attachmentModel;
        }
        return null;

    }

    public function jsonResponse(?AttachmentModel $attachmentModel): JsonResponse|StreamedResponse
    {
        if ($attachmentModel) {

            $headers =[
                'Content-Type' => $attachmentModel->attachment->mime,
            ];

            return response()->streamDownload(function () use ($attachmentModel) {
               echo $attachmentModel->attachment->attachment_data;
            }, $attachmentModel->filename,$headers);


        }else{
            return response()->json(['error' => 'AttachmentModel do not belows to Model'], 400);

        }

    }
}
