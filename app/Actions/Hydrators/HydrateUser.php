<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 30 Jan 2022 19:10:48 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Hydrators;

use App\Models\Auth\User;
use App\Models\Auth\WebsiteUser;
use App\Models\Web\Website;
use Exception;
use Illuminate\Support\Collection;


class HydrateUser extends HydrateModel
{

    public string $commandSignature = 'hydrate:user {id} {--t|tenant=* : Tenant code}';


    public function handle(User $user): void
    {

        $this->syncWebsites($user);
    }

    public function syncWebsites(User $user)
    {

        foreach (Website::all()->pluck('id') as $websiteId) {
            if ($user->hasPermissionTo("websites.view.$websiteId") and $user->status) {
                try {
                    WebsiteUser::create(
                        [
                            'website_id'=>$websiteId,
                            'user_id'=>$user->id,
                        ]
                    );

                }catch (Exception){}
            } else {
                WebsiteUser::where('website_id',$websiteId)->where('user_id',$user->id)->delete();
            }
        }
    }


    protected function getModel(int $id): ?User
    {
        return (new User())->where('id',$id)->where('tenant_id',app('currentTenant')->id)->first();
    }

    protected function getAllModels(): Collection
    {
        return User::withTrashed()->where('tenant_id',app('currentTenant')->id)->get();
    }


}


