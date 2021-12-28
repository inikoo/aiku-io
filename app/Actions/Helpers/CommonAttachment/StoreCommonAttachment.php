<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 20 Dec 2021 13:42:31 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Helpers\CommonAttachment;

use App\Models\Helpers\CommonAttachment;
use App\Models\Utils\ActionResult;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreCommonAttachment
{
    use AsAction;

    public function handle($imagePath, $commonAttachmentData): ActionResult
    {
        $res = new ActionResult();

        $checksum = md5_file($imagePath);

        $commonAttachmentData = array_merge(
            Arr::only($commonAttachmentData, ['mime', 'extension']),
            [
                'checksum'     => $checksum,
                'filesize'     => filesize($imagePath),
                'file_content' => file_get_contents($imagePath),
            ]
        );


        CommonAttachment::upsert([
                                     $commonAttachmentData,
                                 ],
                                 ['checksum'],
                                 ['filesize']
        );

        $commonAttachment = CommonAttachment::firstWhere('checksum', $checksum);

        $res->model    = $commonAttachment;
        $res->model_id = $commonAttachment->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
