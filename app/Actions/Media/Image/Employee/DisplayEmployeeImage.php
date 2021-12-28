<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 28 Dec 2021 18:06:40 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Media\Image\Employee;

use App\Models\HumanResources\Employee;
use App\Models\Media\Image;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\HttpFoundation\StreamedResponse;

use function response;

class DisplayEmployeeImage
{
    use AsAction;

    public function handle(Employee $employee, Image $image): ?Image
    {

        if($image->imageable_type=='Employee' and $image->imageable_id==$employee->id){
            return $image;
        }
        return null;

    }

    public function jsonResponse(?Image $image): JsonResponse|StreamedResponse
    {
        if ($image) {

        /** @var \App\Models\Media\RawImage|\App\Models\Assets\ProcessedImage $imageSrc */
            $imageSrc=$image->communalImage->imageable;


            $headers =[
                'Content-Type' => $imageSrc->mime,
            ];

            return response()->streamDownload(function () use ($imageSrc) {
               echo $imageSrc->image_data;
            }, $image->filename,$headers);


        }else{
            return response()->json(['error' => 'Image do not belows to Model'], 400);

        }

    }
}
