<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 04 Oct 2021 12:35:41 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Distribution\WarehouseArea;

use App\Models\Distribution\WarehouseArea;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateWarehouseArea
{
    use AsAction;

    public function handle(WarehouseArea $area, array $data): WarehouseArea
    {
        $area->update($data);
        return $area;
    }
}
