<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 20 Oct 2021 16:32:23 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Account\Tenant;

use App\Http\Resources\Account\TenantResource;
use App\Models\Account\Tenant;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowTenant
{
    use AsAction;

    public function handle(Tenant $tenant): Tenant
    {
        return $tenant;
    }

    #[Pure] public function jsonResponse(Tenant $tenant): TenantResource
    {
        return new TenantResource($tenant);
    }
}
