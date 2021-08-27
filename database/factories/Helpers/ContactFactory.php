<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 28 Aug 2021 01:21:51 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace Database\Factories\Helpers;

use App\Models\Helpers\Address;
use App\Models\Helpers\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{

    protected $model = Contact::class;


    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'phone' => $this->faker->phoneNumber(),


        ];
    }

    public function configure(): ContactFactory
    {
        return $this->afterMaking(function (Contact $contact) {

        })->afterCreating(function (Contact $contact) {
            Address::factory()->count(1)->for(
                $contact, 'owner'
            )->create();
        });
    }


}
