<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 18 Dec 2021 14:23:22 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace Database\Seeders;

use App\Models\Auth\Role;
use App\Models\HumanResources\JobPosition;
use Illuminate\Database\Seeder;
use Spatie\Multitenancy\Models\Tenant;

class JobPositionSeeder extends Seeder
{

    public function run()
    {
        /** @var \App\Models\Account\Tenant $tenant */
        $tenant = Tenant::current();

        $jobPositions = collect(config("app_type.{$tenant->appType->code}.job_positions.positions"));


        foreach ($jobPositions as $jobPositionData) {
            JobPosition::upsert([
                                    [
                                        'slug' => $jobPositionData['slug'],
                                        'name' => $jobPositionData['name'],
                                        'data' => '{}',
                                        'roles' => '{}'
                                    ],
                                ],
                                ['slug'],
                                ['name']
            );


            $jobPosition = JobPosition::firstWhere('slug', $jobPositionData['slug']);
            $roles       = [];
            foreach ($jobPositionData['roles'] as $roleName) {
                if ($role = Role::where('name', $roleName)->where('team_id', $tenant->appType->id)->first()) {
                    $roles[] = $role->id;
                }
            }

            $jobPosition->update(
                [
                    'roles' => $roles
                ]
            );
        }
    }
}
