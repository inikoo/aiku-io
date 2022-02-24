<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 22 Feb 2022 15:05:27 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace Database\Seeders;

use App\Models\Aiku\AppType;
use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use Exception;
use Illuminate\Database\Seeder;
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


        foreach (AppType::all() as $appType) {
            $permissions = collect(config("app_type.$appType->code.permissions"));
            $roles       = collect(config("app_type.$appType->code.roles"));

            $permissions->each(function ($permission) use ($appType) {
                try {
                    Permission::create(['name' => $permission, 'guard_name' => $appType->code]);
                } catch (Exception) {
                }
            });


            $roles->each(function ($permission_names, $role_name) use ($appType) {
                if (!$role = Role::where('name', $role_name)
                    ->where('team_id', $appType->id)
                    ->where('guard_name', $appType->code)
                    ->first()) {
                    /** @var \App\Models\Auth\Role $role */
                    $role = Role::create(['name' => $role_name, 'team_id' => $appType->id, 'guard_name' => $appType->code]);
                }
                $permissions = [];
                foreach ($permission_names as $permission_name) {


                    if ($permission = Permission::where('guard_name', $appType->code)->where('name', $permission_name)->first()) {
                        $permissions[] = $permission;
                    }
                }

                $role->syncPermissions($permissions);

            });
        }
    }
}
