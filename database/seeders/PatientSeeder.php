<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 27 Aug 2021 18:35:25 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace Database\Seeders;

use App\Models\Health\Patient;
use App\Models\Helpers\Contact;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class PatientSeeder extends Seeder
{

    public function run()
    {
        Patient::factory()
            ->count(50)->hasAttached(
                Contact::factory()->count(1),

                function () {
                    return ['relation' => Arr::random(['Mother','Mother','Father'])];
                }

            )
            ->create();
    }
}
