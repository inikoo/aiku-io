<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 21 Dec 2021 16:14:18 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Helpers\Attachment;

use App\Http\Resources\Helpers\AttachmentResource;
use App\Models\Helpers\Attachment;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowAttachment
{
    use AsAction;

    public function handle(Attachment $attachment): Attachment
    {
        return $attachment;
    }

    /** @noinspection PhpExpressionResultUnusedInspection */
    #[Pure] public function jsonResponse(Attachment $attachment): AttachmentResource
    {
        $attachment->models;
        return new AttachmentResource($attachment);
    }
}
