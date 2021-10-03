<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 29 Sep 2021 20:24:39 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\Helpers\Image\StoreImage;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class MigrateImage
{
    use AsAction;

    public function handle( $auroraImageData)
    {
        if ($imageData = $this->getImageData($auroraImageData)) {
            $image = StoreImage::run(Arr::get($imageData, 'image_path'), Arr::get($imageData, 'mime'));

            $image->aurora_id = $auroraImageData->{'Image Key'};
            $image->created_at = $auroraImageData->{'Image Creation Date'};
            $image->save();



            return $image;
        } else {
            return false;
        }
    }

    protected function getImageData($auroraImageData): bool|array
    {
        $image_path = sprintf(config('app.aurora_image_path'), app('currentTenant')->data['aurora_account_code']).'/'
            .$auroraImageData->{'Image File Checksum'}[0].'/'
            .$auroraImageData->{'Image File Checksum'}[1].'/'
            .$auroraImageData->{'Image File Checksum'}.'.'
            .$auroraImageData->{'Image File Format'};

        // dd($image_path);

        if (file_exists($image_path)) {
            return [
                'image_path' => $image_path,
                'filename'   => $auroraImageData->{'Image Filename'},
                'mime'       => $auroraImageData->{'Image MIME Type'}
            ];
        } else {
            return false;
        }
    }
}
