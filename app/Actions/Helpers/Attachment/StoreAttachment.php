<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 20 Dec 2021 13:42:31 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Helpers\Attachment;

use App\Models\Helpers\Attachment;
use App\Models\Utils\ActionResult;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreAttachment
{
    use AsAction;

    public function handle($imagePath, $mime): ActionResult
    {
        $res = new ActionResult();

        $checksum = md5_file($imagePath);

        Attachment::upsert([
                               [
                                   'checksum'        => $checksum,
                                   'filesize'        => filesize($imagePath),
                                   'mime'            => $mime,
                                   'data'            => '{}',
                                   'attachment_data' => pg_escape_bytea(file_get_contents($imagePath)),

                               ],
                           ],
                           ['checksum'],
                           ['mime']
        );

        $attachment = Attachment::firstWhere('checksum', $checksum);

        $res->model    = $attachment;
        $res->model_id = $attachment->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
