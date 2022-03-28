<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 29 Mar 2022 00:48:45 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Auth\User;


use App\Actions\Hydrators\HydrateUser;
use App\Models\Auth\User;
use Lorisleiva\Actions\Concerns\AsAction;



class SyncUserRoles
{
    use AsAction;

    public function handle(User $user, array $roles)
    {
        $user->syncRoles($roles);
        HydrateUser::make()->syncWebsites($user);

    }
}


