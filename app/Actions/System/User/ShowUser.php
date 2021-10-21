<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 20 Oct 2021 16:32:23 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\System\User;

use App\Http\Resources\System\UserResource;
use App\Models\System\User;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowUser
{
    use AsAction;

    public function handle(User $user): User
    {
        return $user;
    }

    #[Pure] public function jsonResponse(User $user): UserResource
    {
        return new UserResource($user);
    }
}
