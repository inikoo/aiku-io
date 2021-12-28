<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 28 Dec 2021 01:00:55 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Events;

use App\Models\Helpers\CommonAttachmentTenant;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommonAttachmentAnchoring
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;


    public int $commonAttachmentID;

    public function __construct(CommonAttachmentTenant $commonAttachmentTenant)
    {
        $this->commonAttachmentID=$commonAttachmentTenant->common_attachment_id;
    }


}
