<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 28 Dec 2021 01:02:27 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Listeners;

use App\Events\CommonAttachmentAnchoring;
use App\Models\Helpers\CommonAttachment;
use App\Providers\AttachmentDeleted;


class UpdateCommonAttachmentStats
{

    public function __construct()
    {
        //
    }


    public function handle(AttachmentDeleted|CommonAttachmentAnchoring $event)
    {
        $commonAttachment=CommonAttachment::find($event->commonAttachmentID);
        $commonAttachment->update($commonAttachment->getRelationsCounts());

    }
}
