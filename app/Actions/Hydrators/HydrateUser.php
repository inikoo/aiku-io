<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 30 Jan 2022 19:10:48 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Hydrators;

use App\Models\Auth\User;
use Illuminate\Support\Collection;


class HydrateUser extends HydrateModel
{

    public string $commandSignature = 'hydrate:user {id} {--t|tenant=* : Tenant code}';


    public function handle(User $user): void
    {
        $user->update(
            [
                'name' =>
                    match ($user->userable_type) {
                        'Tenant' => 'Account Admin',
                        default => $user->userable->name
                    }


            ]
        );
    }

    protected function getModel(int $id): User
    {
        return User::find($id);
    }

    protected function getAllModels(): Collection
    {
        return User::withTrashed()->get();
    }


}


