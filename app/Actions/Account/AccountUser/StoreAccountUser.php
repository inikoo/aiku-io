<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 24 Sep 2021 17:32:41 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Account\AccountUser;

use App\Models\Account\AccountAdmin;
use App\Models\Account\Tenant;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreAccountUser
{
    use AsAction;


    public function handle( Tenant|AccountAdmin $userable, array $userData): Model
    {
        return $userable->accountUser()->create($userData);
    }
}
