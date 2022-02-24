<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 04 Oct 2021 11:46:02 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\Warehouse;

use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Models\Inventory\Warehouse;
use App\Models\Utils\ActionResult;
use Exception;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Permission\PermissionRegistrar;

class StoreWarehouse
{
    use AsAction;

    public function handle($data): ActionResult
    {
        $res = new ActionResult();

        $warehouse = Warehouse::create($data);
        $warehouse->stats()->create();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        /** @var \App\Models\Account\Tenant $tenant */
        $tenant      = Tenant::current();
        $permissions = collect(config("app_type.{$tenant->appType->code}.model_permissions.Warehouse"))->map(function ($name) use ($warehouse) {
            return preg_replace('/#/', $warehouse->id, $name);
        });
        $permissions->diff(Permission::all()->pluck('name'))->each(function ($permission) {
            try {
                Permission::create(['name' => $permission]);
            } catch (Exception) {
            }
        });

        $roles = collect(config("app_type.{$tenant->appType->code}.model_roles.Warehouse"))
            ->map(function ($name) use ($warehouse) {
            return preg_replace('/#/', $warehouse->id, $name);
        });

        $roles->keys()
            ->map(function ($role) use ($warehouse) {
                return preg_replace('/#/', $warehouse->id, $role);})
            ->diff(Role::where('team_id',$tenant->appType->id)->pluck('name'))->each(function ($role) use ($warehouse,$tenant) {
            try {
                Role::create(['name' => preg_replace('/#/', $warehouse->id, $role), 'team_id' => $tenant->appType->id]);
            } catch (Exception) {
            }
        });
        $roles->each(function ($permissions, $role_name) use ($warehouse,$tenant) {
            try {
                Role::where('name', preg_replace('/#/', $warehouse->id, $role_name))
                    ->where('team_id', $tenant->appType->id)
                    ->first()->syncPermissions($permissions);
            } catch (Exception) {
            }
        });

        $res->model    = $warehouse;
        $res->model_id = $warehouse->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
