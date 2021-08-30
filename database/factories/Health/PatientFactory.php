<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 27 Aug 2021 19:01:55 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace Database\Factories\Health;

use App\Models\Health\Patient;
use App\Models\Helpers\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{

    protected $model = Patient::class;

    public function definition(): array
    {
        return [
            'type'=>'adult',
            'contact_id' => Contact::factory(),
        ];
    }

    public function isDependant(): PatientFactory
    {

        return $this->state(function () {
            /** @noinspection PhpPossiblePolymorphicInvocationInspection */
            return [
                'contact_id' => Contact::factory()->forDependantPatient()->create(),
                'type'=>'dependant'
            ];
        });
    }

}
