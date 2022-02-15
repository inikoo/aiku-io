<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 15 Feb 2022 01:22:55 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Marketing\Family;

use App\Models\Marketing\Department;
use App\Models\Marketing\Shop;
use App\Models\Utils\ActionResult;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreFamily
{
    use AsAction;

    public function handle(Department|Shop $parent, array $modelData): ActionResult
    {
        $res = new ActionResult();

        if(class_basename($parent::class)=='Department'){
            $modelData['shop_id']=$parent->shop_id;

        }
        /** @var \App\Models\Marketing\Family $family */

        $family = $parent->families()->create($modelData);


        $family->stats()->create();
        $family->salesStats()->create([
                                           'scope' => 'sales'
                                       ]);
        if ($family->shop->currency_id != app('currentTenant')->currency_id) {
            $family->salesStats()->create([
                                               'scope' => 'sales-tenant-currency'
                                           ]);
        }


        $res->model    = $family;
        $res->model_id = $family->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
