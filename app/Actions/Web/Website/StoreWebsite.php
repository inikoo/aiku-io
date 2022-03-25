<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 14 Jan 2022 01:45:10 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Web\Website;

use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Models\Marketing\Shop;
use App\Models\Utils\ActionResult;
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
        $website->stats()->create();
        $website->layout()->create();
        app('currentTenant')->tenantWebsites()->create(
            [
                'code'=>$website->code,
                'domain'=>$website->url,
                'website_id'=>$website->id,
                'type'=>$website->shop->subtype
            ]
        );

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        /** @var \App\Models\Account\Tenant $tenant */
        $tenant      = Tenant::current();
        $permissions = collect(config("app_type.{$tenant->appType->code}.model_permissions.Website"))->map(function ($name) use ($website) {
            return preg_replace('/#/', $website->id, $name);
        });
        $permissions->diff(Permission::all()->pluck('name'))->each(function ($permission) {
            Permission::create(['name' => $permission]);
        });

        $roles = collect(config("app_type.{$tenant->appType->code}.model_roles.Website"))->map(function ($name) use ($website) {
            return preg_replace('/#/', $website->id, $name);
        });

        $roles->keys()
            ->map(function ($role) use ($website) {
                return preg_replace('/#/', $website->id, $role);})
            ->diff((new Role())->where('team_id', $tenant->appType->id)->pluck('name'))->each(function ($role) use ($tenant,$website) {
            Role::create(['name' => preg_replace('/#/', $website->id, $role), 'team_id' => $tenant->appType->id]);
        });
        $roles->each(function ($permissions, $role_name) use ($website,$tenant) {
            (new Role())->where('name', preg_replace('/#/', $website->id, $role_name))
                ->where('team_id', $tenant->appType->id)
                ->first()->syncPermissions($permissions);
        });


        $res->model    = $website;
        $res->model_id = $website->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }


}
