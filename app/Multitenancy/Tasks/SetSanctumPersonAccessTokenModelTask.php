<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 22 Sep 2021 21:27:42 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Multitenancy\Tasks;

use App\Models\Auth\LandlordPersonalAccessToken;
use App\Models\Auth\TenantPersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;

class SetSanctumPersonAccessTokenModelTask implements SwitchTenantTask
{

    public function makeCurrent(Tenant $tenant): void
    {
        Sanctum::usePersonalAccessTokenModel(TenantPersonalAccessToken::class);
    }

    public function forgetCurrent(): void
    {
        Sanctum::usePersonalAccessTokenModel(LandlordPersonalAccessToken::class);
    }
}


