<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 14 Jan 2022 01:45:10 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Web\Website;

use App\Models\System\Permission;
use App\Models\System\Role;
use App\Models\Utils\ActionResult;
use App\Models\Marketing\Shop;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Permission\PermissionRegistrar;


class StoreWebsite
{
    use AsAction;

    public function handle(Shop $shop,array $data): ActionResult
    {
        $res  = new ActionResult();

        /** @var \App\Models\Web\Website $website */
        $website = $shop->website()->create($data);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        /** @var \App\Models\Account\Tenant $tenant */
        $tenant      = Tenant::current();
        $permissions = collect(config("division.{$tenant->division->slug}.model_permissions.Website"))->map(function ($name) use ($shop) {
            return preg_replace('/#/', $shop->id, $name);
        });
        $permissions->diff(Permission::all()->pluck('name'))->each(function ($permission) {
            Permission::create(['name' => $permission]);
        });

        $roles = collect(config("division.{$tenant->division->slug}.model_roles.Website"))->map(function ($name) use ($shop) {
            return preg_replace('/#/', $shop->id, $name);
        });

        $roles->keys()->diff(Role::all()->pluck('name'))->each(function ($role) use ($shop) {
            Role::create(['name' => preg_replace('/#/', $shop->id, $role)]);
        });
        $roles->each(function ($permissions, $role_name) use ($shop) {
            Role::where('name', preg_replace('/#/', $shop->id, $role_name))->first()->syncPermissions($permissions);
        });


        $res->model    = $website;
        $res->model_id = $website->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }


}
