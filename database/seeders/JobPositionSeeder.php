<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 18 Dec 2021 14:23:22 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace Database\Seeders;

use App\Models\HumanResources\JobPosition;
use App\Models\System\Role;
use Illuminate\Database\Seeder;
use Spatie\Multitenancy\Models\Tenant;

class JobPositionSeeder extends Seeder
{

    public function run()
    {
        /** @var \App\Models\Account\Tenant $tenant */
        $tenant = Tenant::current();

        $jobPositions = collect(config("division.{$tenant->division->slug}.job_positions"));


        foreach ($jobPositions as $jobPositionData) {
            JobPosition::upsert([
                                    [
                                        'slug' => $jobPositionData['slug'],
                                        'name' => $jobPositionData['name'],
                                        'data' => '{}'
                                    ],
                                ],
                                ['slug'],
                                ['name']
            );


            $jobPosition = JobPosition::firstWhere('slug', $jobPositionData['slug']);
            $roles       = [];
            foreach ($jobPositionData['roles'] as $roleName) {
                if ($role = Role::firstWhere('name', $roleName)) {
                    $roles[] = $role->id;
                }
            }
            $jobPosition->roles()->sync($roles);

        }
    }
}
