<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 28 Dec 2021 17:46:06 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Media\RawImage;

use App\Models\Media\CommunalImage;
use App\Models\Media\RawImage;
use App\Models\Utils\ActionResult;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreRawImage
{
    use AsAction;

    public function handle($imagePath, array $rawImageData): ActionResult
    {
        $res = new ActionResult();


        $size_data = getimagesize($imagePath);
        $width     = $size_data[0];
        $height    = $size_data[0];

        $checksum = md5_file($imagePath);


        $rawImageData = array_merge(   [
                                        'checksum'   => $checksum,
                                        'filesize' => filesize($imagePath),
                                        'megapixels' => $width * $height / 1000000,
                                        'image_data' => file_get_contents($imagePath),
                                    ], $rawImageData);

        RawImage::upsert([
                             $rawImageData
                         ],
                         ['checksum'],
                         ['checksum']
        );

        $rawImage = RawImage::firstWhere('checksum', $checksum);


        if (!$rawImage->communalImage) {
            $rawImage->communalImage()->save(new CommunalImage());
            $rawImage->refresh();
        }


        // TODO: Hack to set the created date while migration delete it when aurora migrations are done

        if (Arr::exists($rawImageData, 'created_at')) {
            $currentDateFrmMigration = Carbon::parse(Arr::get($rawImageData, 'created_at'));
            if ($rawImage->created_at->gt($currentDateFrmMigration)) {
                $rawImage->update(
                    [
                        'created_at' => $currentDateFrmMigration
                    ]
                );
            }
            $rawImage->communalImage->update(
                [
                    'created_at' => $rawImage->created_at
                ]
            );
        }
        //=====================================

        $res->model    = $rawImage;
        $res->model_id = $rawImage->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';


        return $res;
    }
}
