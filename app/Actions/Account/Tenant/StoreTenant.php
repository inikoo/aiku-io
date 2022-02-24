<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 24 Sep 2021 16:15:33 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Account\Tenant;

use App\Models\Aiku\AppType;
use App\Models\Assets\Currency;
use App\Models\Utils\ActionResult;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreTenant
{
    use AsAction;

    public function handle(AppType $appType, array $tenantData): ActionResult
    {
        $res = new ActionResult();

        /** @var \App\Models\Account\Tenant $tenant */
        $tenant = $appType->tenants()->create($tenantData);
        $tenant->stats()->create();
        $tenant->marketingStats()->create();
        $tenant->inventoryStats()->create();
        $tenant->procurementStats()->create();


        $tenant->salesStats()->create(['currency_id' => $tenant->currency_id]);

        foreach (Currency::whereIn('code', ['USD', 'GBP','EUR'])->get()->pluck('id') as $currencyID) {
            if ($currencyID != $tenant->currency_id) {
                $tenant->salesStats()->create(['currency_id' => $currencyID]);
            }
        }

        $res->model    = $tenant;
        $res->model_id = $tenant->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
