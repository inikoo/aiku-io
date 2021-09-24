<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 24 Sep 2021 16:15:33 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Account\Tenant;

use App\Models\Account\BusinessType;
use App\Models\Account\Tenant;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreTenant
{
    use AsAction;

    public function handle(BusinessType $businessType, array $tenantData): Tenant|Model
    {
        return $businessType->tenants()->create($tenantData);
    }
}
