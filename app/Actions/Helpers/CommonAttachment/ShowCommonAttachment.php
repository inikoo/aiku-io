<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 21 Dec 2021 16:14:18 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Helpers\CommonAttachment;

use App\Http\Resources\Helpers\CommonAttachmentResource;
use App\Models\Helpers\CommonAttachment;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowCommonAttachment
{
    use AsAction;

    public function handle(CommonAttachment $attachment): CommonAttachment
    {
        return $attachment;
    }

    #[Pure] public function jsonResponse(CommonAttachment $attachment): CommonAttachmentResource
    {
        return new CommonAttachmentResource($attachment);
    }
}
