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
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;


class PatientSeeder extends Seeder
{

    public function run()
    {


        $numberPatients = 50;

        $faker = Factory::create();

        foreach (range(1, $numberPatients) as $ignored) {
            if ($faker->boolean()) {
                /** @noinspection PhpPossiblePolymorphicInvocationInspection */
                Patient::factory()
                    ->count(1)->isDependant()
                    ->create()->each(
                        function ($patient) {
                            $patient->guardians()->attach(Contact::factory()->create(), [
                                'relation' => Arr::random(['Mother', 'Mother', 'Father']
                                )
                            ]);
                        }

                    );
            } else {
                Patient::factory()->count(1)->create();
            }
        }
    }


}
