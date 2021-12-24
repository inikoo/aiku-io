<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 23 Dec 2021 00:41:18 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Helpers\Attachment\Employee;

use App\Models\Helpers\Attachment;
use App\Models\HumanResources\Employee;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadEmployeeAttachment
{
    use AsAction;

    public function handle(Employee $employee, Attachment $attachment): ?Attachment
    {

        if($attachment->attachmentable_type=='Employee' and $attachment->attachmentable_id==$employee->id){
            return $attachment;
        }
        return null;

    }

    public function jsonResponse(?Attachment $attachment): JsonResponse|StreamedResponse
    {
        if ($attachment) {

            $headers =[
                'Content-Type' => $attachment->attachment->mime,
            ];

            return response()->streamDownload(function () use ($attachment) {
               echo $attachment->attachment->file_content;
            }, $attachment->filename,$headers);


        }else{
            return response()->json(['error' => 'Attachment do not belows to Model'], 400);

        }

    }
}
