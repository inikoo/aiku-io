<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 24 Sep 2021 16:15:33 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Account\Tenant;

use App\Models\Utils\ActionResult;
use App\Models\Account\Division;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreTenant
{
    use AsAction;

    public function handle(Division $division, array $tenantData): ActionResult
    {
        $res = new ActionResult();

        /** @var \App\Models\Account\Tenant $tenant */
        $tenant        = $division->tenants()->create($tenantData);
        $res->model    = $tenant;
        $res->model_id = $tenant->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
