<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 03 Jan 2022 00:44:32 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace Database\Seeders;

use App\Models\HumanResources\WorkSchedule;
use Illuminate\Database\Seeder;

class WorkScheduleSeeder extends Seeder
{

    public function run()
    {

        $workScheduleTypes=['vacation','workplace-closed','festivity','rest-day'];


        foreach ($workScheduleTypes as $type) {



            WorkSchedule::create(
                [
                    'type'=>$type
                ]
            );

        }

    }
}
