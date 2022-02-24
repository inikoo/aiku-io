<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 25 Sep 2021 11:45:56 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Marketing\Shop;

use App\Actions\Helpers\Address\StoreAddress;
use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Models\Marketing\Shop;
use App\Models\Utils\ActionResult;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Permission\PermissionRegistrar;

class StoreShop
{
    use AsAction;

    public function handle(array $modelData, array $addressData = []): ActionResult
    {
        $res  = new ActionResult();
        $shop = Shop::create($modelData);
        $shop->stats()->create();
        $shop->salesStats()->create([
                                        'scope' => 'sales'
                                    ]);
        if ($shop->currency_id != app('currentTenant')->currency_id) {
            $shop->salesStats()->create([
                                            'scope' => 'sales-tenant-currency'
                                        ]);
        }


        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        /** @var \App\Models\Account\Tenant $tenant */
        $tenant = Tenant::current();


        $permissions = collect(config("app_type.{$tenant->appType->code}.model_permissions.Shop"))->map(function ($name) use ($shop) {
            return preg_replace('/#/', $shop->id, $name);
        });
        $permissions->diff(Permission::all()->pluck('name'))->each(function ($permission) {
            Permission::create(['name' => $permission]);
        });

        $roles = collect(config("app_type.{$tenant->appType->code}.model_roles.Shop"))
            ->map(function ($name) use ($shop) {
                return preg_replace('/#/', $shop->id, $name);
            });

        $roles->keys()
            ->map(function ($role) use ($shop) {
            return preg_replace('/#/', $shop->id, $role);})
            ->diff((new Role())->where('team_id', $tenant->appType->id)->pluck('name'))->each(function ($role) use ($shop, $tenant) {
            Role::create(['name' => $role, 'team_id' => $tenant->appType->id]);
        });
        $roles->each(function ($permissions, $role_name) use ($shop, $tenant) {
            (new Role())->where('name', preg_replace('/#/', $shop->id, $role_name))
                ->where('team_id', $tenant->appType->id)
                ->first()->syncPermissions($permissions);
        });

        $address = StoreAddress::run($addressData);

        $shop->address_id = $address->id;
        $shop->location   = $shop->getLocation();
        $shop->save();

        $res->model    = $shop;
        $res->model_id = $shop->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }


}
