<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 27 Aug 2021 19:01:55 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace Database\Factories\Health;

use App\Models\Health\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class PatientFactory extends Factory
{

    protected $model = Patient::class;



    public function definition(): array
    {
        return [
            'name'          => $this->faker->name(),
            'date_of_birth' => $this->faker->dateTime(),
            'gender'        => function () {
                return Arr::random(['Male', 'Female']);
            },
            'identity_document_type'=>function () {
                return match (config('app.faker_locale')) {
                    'en_GB' => 'NI number',
                    'ms_MY' => 'MyKad',
                    default => 'Passport',
                };


            },
            'identity_document_number'=>function () {
                return match (config('app.faker_locale')) {
                    'en_GB' => $this->faker->nino(),
                    'ms_MY' => $this->faker->myKadNumber(),
                    default => $this->faker->shuffle('123456789ABC'),
                };


            }




        ];
    }

}
