<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 15 Jan 2022 15:09:30 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Production\Workshop;

use App\Models\Utils\ActionResult;
use App\Models\Account\Tenant;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreWorkshop
{
    use AsAction;

    public function handle(Tenant  $parent, array $modelData): ActionResult
    {
        $res  = new ActionResult();

        /** @var \App\Models\Production\Workshop $workshop */
        $workshop = $parent->workshops()->create($modelData);


        $res->model    = $workshop;
        $res->model_id = $workshop->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;    }
}
