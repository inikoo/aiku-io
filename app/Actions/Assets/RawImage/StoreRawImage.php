<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 27 Dec 2021 21:00:49 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Assets\RawImage;

use App\Models\Media\CommunalImage;
use App\Models\Media\RawImage;
use App\Models\Utils\ActionResult;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreRawImage
{
    use AsAction;

    public function handle($imagePath, $mime):ActionResult
    {
        $res = new ActionResult();


        $size_data = getimagesize($imagePath);
        $width     = $size_data[0];
        $height    = $size_data[0];

        $checksum=md5_file($imagePath);

        RawImage::upsert([
                             [
                                 'checksum'   => $checksum,
                                 'filesize'   => filesize($imagePath),
                                 'megapixels' => $width * $height / 1000000,
                                 'mime'       => $mime,
                                 'image_data' => file_get_contents($imagePath),

                             ],
                         ],
                         ['checksum'],
                         ['checksum']
        );

        $rawImage = RawImage::firstWhere('checksum',$checksum);


        if (!$rawImage->communalImage) {


            $rawImage->communalImage()->save(new CommunalImage());
            $rawImage->refresh();

        }

/*

        $image = Image::where(
            'communal_image_id',
            $rawImage->communalImage->id
        )->first();
        if (!$image) {
            $image = Image::create(
                [
                    'communal_image_id' => $rawImage->communalImage->id,

                ]
            );
        }


        return $image;

*/

        $res->model    = $rawImage;
        $res->model_id = $rawImage->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';


        return $res;


    }
}
