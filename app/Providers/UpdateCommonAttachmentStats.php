<?php

namespace App\Providers;

use App\Models\Helpers\CommonAttachment;


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
