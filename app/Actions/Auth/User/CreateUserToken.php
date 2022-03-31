<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 29 Mar 2022 00:42:13 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Auth\User;

use App\Models\Auth\LandlordPersonalAccessToken;
use App\Models\Auth\User;
use Laravel\Sanctum\Sanctum;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateUserToken
{
    use AsAction;

    public function handle(User $user): string
    {
        Sanctum::usePersonalAccessTokenModel(LandlordPersonalAccessToken::class);
        return $user->createToken('user-api')->plainTextToken;
    }


    public function asController(User $user): string
    {
        return $this->handle(
            $user,
        );
    }
}
