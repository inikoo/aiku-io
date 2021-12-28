<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 28 Dec 2021 01:02:27 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Listeners;

use App\Events\CommunalImageAnchoring;



class UpdateCommunalImageStats
{

    public function __construct()
    {
        //
    }


    public function handle(CommunalImageAnchoring $event)
    {
        $event->communalImage->update($event->communalImage->getRelationsCounts());

    }
}
