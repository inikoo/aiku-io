<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 04 Oct 2021 11:46:02 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Distribution\Warehouse;

use App\Models\Distribution\Warehouse;
use App\Models\System\Permission;
use App\Models\System\Role;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Permission\PermissionRegistrar;

class StoreWarehouse
{
    use AsAction;

    public function handle($data): Warehouse
    {
        $warehouse= Warehouse::create($data);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        /** @var \App\Models\Account\Tenant $tenant */
        $tenant      = Tenant::current();
        $permissions = collect(config("business_types.{$tenant->businessType->slug}.model_permissions.Warehouse"))->map(function ($name) use ($warehouse) {
            return preg_replace('/#/',$warehouse->id,$name);
        });
        $permissions->diff(Permission::all()->pluck('name'))->each(function ($permission) {
            Permission::create(['name' => $permission]);
        });

        $roles= collect(config("business_types.{$tenant->businessType->slug}.model_roles.Warehouse"))->map(function ($name) use ($warehouse){
            return preg_replace('/#/',$warehouse->id,$name);
        });

        $roles->keys()->diff(Role::all()->pluck('name'))->each(function ($role) use ($warehouse){
            Role::create(['name' => preg_replace('/#/',$warehouse->id,$role)]);
        });
        $roles->each(function ($permissions, $role_name) use ($warehouse) {
            Role::where('name', preg_replace('/#/',$warehouse->id,$role_name))->first()->syncPermissions($permissions);
        });

        return $warehouse;

    }
}
