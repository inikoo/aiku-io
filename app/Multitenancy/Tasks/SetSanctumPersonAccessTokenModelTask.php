<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 22 Sep 2021 21:27:42 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Multitenancy\Tasks;

use App\Models\System\PersonalAccessToken;
use Laravel\Sanctum\PersonalAccessToken as BasePersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;

class SetSanctumPersonAccessTokenModelTask implements SwitchTenantTask
{

    public function makeCurrent(Tenant $tenant): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }

    public function forgetCurrent(): void
    {
        Sanctum::usePersonalAccessTokenModel(BasePersonalAccessToken::class);
    }
}


