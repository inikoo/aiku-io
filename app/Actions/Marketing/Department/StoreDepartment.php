<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 14 Feb 2022 18:50:50 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Marketing\Department;

use App\Models\Utils\ActionResult;
use App\Models\Marketing\Shop;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreDepartment
{
    use AsAction;

    public function handle(Shop $shop, array $modelData): ActionResult
    {
        $res = new ActionResult();

        /** @var \App\Models\Marketing\Department $department */
        $department = $shop->departments()->create($modelData);
        $department->stats()->create();
        $department->salesStats()->create([
                                           'scope' => 'sales'
                                       ]);
        if ($department->shop->currency_id != app('currentTenant')->currency_id) {
            $department->salesStats()->create([
                                               'scope' => 'sales-tenant-currency'
                                           ]);
        }


        $res->model    = $department;
        $res->model_id = $department->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
