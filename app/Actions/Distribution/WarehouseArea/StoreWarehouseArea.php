<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 04 Oct 2021 12:34:45 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Distribution\WarehouseArea;

use App\Models\Distribution\Warehouse;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreWarehouseArea
{
    use AsAction;

    public function handle(Warehouse $warehouse, array $data): Model
    {
        return $warehouse->areas()->create($data);

    }
}
