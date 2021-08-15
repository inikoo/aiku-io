<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 16 Aug 2021 06:31:01 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /** @var \App\Models\Tenant $tenant */
        $tenant = Tenant::current();


        $permissions = collect(config("permission.permissions.$tenant->type"));
        $roles       = collect(config("permission.roles.$tenant->type"));

        $permissions->diff(Permission::all()->pluck('name'))->each(function ($permission) {
            Permission::create(['name' => $permission]);
        });

        $roles->keys()->diff(Role::all()->pluck('name'))->each(function ($role) {
            Role::create(['name' => $role]);
        });

        $roles->each(function ($permissions, $role_name) {
            Role::where('name', $role_name)->first()->syncPermissions($permissions);
        });
    }
}
