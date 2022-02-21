<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 24 Sep 2021 17:32:41 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Account\TenantUser;

use App\Models\Account\Tenant;
use App\Models\Utils\ActionResult;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreTenantUser
{
    use AsAction;


    public function handle(Tenant $tenant, array $userData): ActionResult
    {
        $res = new ActionResult();
        /** @var \App\Models\Account\TenantUser $tenantUser */
        $tenantUser    = $tenant->tenantUser()->create($userData);
        $res->model    = $tenantUser;
        $res->model_id = $tenantUser->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
