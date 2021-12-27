<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 27 Dec 2021 15:02:32 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Providers;

use App\Models\Helpers\Attachment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AttachmentDeleted
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;


    public int $commonAttachmentID;

    public function __construct(Attachment $attachment)
    {
        $this->commonAttachmentID=$attachment->common_attachment_id;
    }


}
