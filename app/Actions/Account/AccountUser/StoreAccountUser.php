<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 24 Sep 2021 17:32:41 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Account\AccountUser;

use App\Models\Utils\ActionResult;
use App\Models\Account\AccountAdmin;
use App\Models\Account\Tenant;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreAccountUser
{
    use AsAction;


    public function handle( Tenant|AccountAdmin $userable, array $userData): ActionResult
    {
        $res  = new ActionResult();

        /** @var \App\Models\Account\AccountUser $accountUser */
        $accountUser= $userable->accountUser()->create($userData);
        $res->model    = $accountUser;
        $res->model_id = $accountUser->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
