<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 29 Sep 2021 20:47:42 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Helpers\Image;

use App\Models\Assets\CommunalImage;
use App\Models\Assets\RawImage;
use App\Models\Helpers\Image;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreImage
{
    use AsAction;

    public function handle($imagePath, $mime): Model|Image
    {
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
    }
}
