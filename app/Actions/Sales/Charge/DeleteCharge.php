<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 19 Nov 2021 23:49:46 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Sales\Charge;

use App\Models\Sales\Charge;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteCharge
{
    use AsAction;

    public function handle(Charge $charge): ?bool
    {
        return $charge->delete();
    }
}
