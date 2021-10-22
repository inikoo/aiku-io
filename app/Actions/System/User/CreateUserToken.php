<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 21 Oct 2021 23:53:52 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\System\User;

use App\Models\System\User;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateUserToken
{
    use AsAction;

    public function handle(User $user): string
    {
        return $user->createToken('user-api')->plainTextToken;
    }


    public function asController(User $user): string
    {
        return $this->handle(
            $user,
        );
    }
}
