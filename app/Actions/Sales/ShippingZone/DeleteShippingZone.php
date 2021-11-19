<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 19 Nov 2021 14:16:23 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Sales\ShippingZone;

use App\Models\Sales\ShippingZone;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteShippingZone
{
    use AsAction;

    public function handle(ShippingZone $shippingZone): ?bool
    {
        return $shippingZone->delete();
    }
}
