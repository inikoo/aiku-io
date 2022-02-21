<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 24 Sep 2021 17:32:41 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Admin\AdminUser;

use App\Models\Admin\AccountAdmin;
use App\Models\Utils\ActionResult;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreAdminUser
{
    use AsAction;


    public function handle(AccountAdmin $accountAdmin, array $userData): ActionResult
    {
        $res  = new ActionResult();

        /** @var \App\Models\Admin\AdminUser $adminUser */
        $adminUser= $accountAdmin->adminUser()->create($userData);
        $res->model    = $adminUser;
        $res->model_id = $adminUser->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
