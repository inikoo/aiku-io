<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 25 Sep 2021 11:45:56 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Trade\Shop;

use App\Models\Utils\ActionResult;
use App\Models\Trade\Shop;
use App\Models\System\Permission;
use App\Models\System\Role;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Permission\PermissionRegistrar;

class StoreShop
{
    use AsAction;

    public function handle(array $data, array $contactData): ActionResult
    {
        $res  = new ActionResult();
        $shop = Shop::create($data);
        $shop->contact()->create($contactData);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        /** @var \App\Models\Account\Tenant $tenant */
        $tenant      = Tenant::current();
        $permissions = collect(config("business_types.{$tenant->businessType->slug}.model_permissions.Shop"))->map(function ($name) use ($shop) {
            return preg_replace('/#/', $shop->id, $name);
        });
        $permissions->diff(Permission::all()->pluck('name'))->each(function ($permission) {
            Permission::create(['name' => $permission]);
        });

        $roles = collect(config("business_types.{$tenant->businessType->slug}.model_roles.Shop"))->map(function ($name) use ($shop) {
            return preg_replace('/#/', $shop->id, $name);
        });

        $roles->keys()->diff(Role::all()->pluck('name'))->each(function ($role) use ($shop) {
            Role::create(['name' => preg_replace('/#/', $shop->id, $role)]);
        });
        $roles->each(function ($permissions, $role_name) use ($shop) {
            Role::where('name', preg_replace('/#/', $shop->id, $role_name))->first()->syncPermissions($permissions);
        });


        $res->model    = $shop;
        $res->model_id = $shop->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }


}
