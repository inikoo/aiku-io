<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 04 Oct 2021 11:46:02 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Distribution\Warehouse;

use App\Models\Distribution\Warehouse;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreWarehouse
{
    use AsAction;

    public function handle($data): Warehouse
    {
        return Warehouse::create($data);

    }
}
