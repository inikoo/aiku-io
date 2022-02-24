<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 23 Feb 2022 00:36:58 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Multitenancy\Tasks;


use Spatie\Multitenancy\Models\Tenant;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;

class SetPermissionsConfigTask implements SwitchTenantTask
{

    public function makeCurrent(Tenant $tenant): void
    {
        setPermissionsTeamId($tenant->appType->id);

    }

    public function forgetCurrent(): void
    {

    }
}


