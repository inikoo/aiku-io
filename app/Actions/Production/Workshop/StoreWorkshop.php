<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 15 Jan 2022 15:09:30 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Production\Workshop;

use App\Models\System\Permission;
use App\Models\System\Role;
use App\Models\Utils\ActionResult;
use App\Models\Account\Tenant;
use Exception;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\Permission\PermissionRegistrar;

class StoreWorkshop
{
    use AsAction;

    public function handle(Tenant  $parent, array $modelData): ActionResult
    {
        $res  = new ActionResult();

        /** @var \App\Models\Production\Workshop $workshop */
        $workshop = $parent->workshops()->create($modelData);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        /** @var \App\Models\Account\Tenant $tenant */
        $tenant      = \Spatie\Multitenancy\Models\Tenant::current();
        $permissions = collect(config("business_types.{$tenant->businessType->slug}.model_permissions.Workshop"))->map(function ($name) use ($workshop) {
            return preg_replace('/#/', $workshop->id, $name);
        });
        $permissions->diff(Permission::all()->pluck('name'))->each(function ($permission) {
            try {
                Permission::create(['name' => $permission]);
            } catch (Exception) {
            }
        });

        $roles = collect(config("business_types.{$tenant->businessType->slug}.model_roles.Workshop"))->map(function ($name) use ($workshop) {
            return preg_replace('/#/', $workshop->id, $name);
        });

        $roles->keys()->diff(Role::all()->pluck('name'))->each(function ($role) use ($workshop) {
            try {
                Role::create(['name' => preg_replace('/#/', $workshop->id, $role)]);
            } catch (Exception) {
            }
        });
        $roles->each(function ($permissions, $role_name) use ($workshop) {
            try {
                Role::where('name', preg_replace('/#/', $workshop->id, $role_name))->first()->syncPermissions($permissions);
            } catch (Exception) {
            }
        });



        $res->model    = $workshop;
        $res->model_id = $workshop->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;    }
}
