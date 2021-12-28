<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 28 Dec 2021 01:00:55 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Events;

use App\Models\Media\CommunalImage;
use App\Models\Media\CommunalImageTenant;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommunalImageAnchoring
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;


    public CommunalImage $communalImage;

    public function __construct(CommunalImageTenant $communalImageTenant)
    {
        $this->communalImage=$communalImageTenant->communalImage;
    }


}
