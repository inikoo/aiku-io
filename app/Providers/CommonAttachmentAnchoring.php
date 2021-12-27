<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 27 Dec 2021 15:19:35 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Providers;

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
