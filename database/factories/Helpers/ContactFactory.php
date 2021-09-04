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
use Illuminate\Support\Arr;

class ContactFactory extends Factory
{

    protected $model = Contact::class;


    public function definition(): array
    {
        return [
            'name'                     => $this->faker->name(),
            'date_of_birth'            => $this->faker->dateTimeBetween('-40 years','-18 years'),
            'gender'                   => function () {
                return Arr::random(['male', 'female']);
            },
            'email'                    => $this->faker->email(),
            'phone'                    => $this->faker->phoneNumber(),
            'identity_document_type'   => function () {
                return match (config('app.faker_locale')) {
                    'en_GB' => 'NI number',
                    'ms_MY' => 'MyKad',
                    default => 'Passport',
                };
            },
            'identity_document_number' => function () {
                /** @noinspection PhpUndefinedMethodInspection */
                return match (config('app.faker_locale')) {
                    'en_GB' => $this->faker->nino(),
                    'ms_MY' => $this->faker->myKadNumber(),
                    default => $this->faker->shuffle('123456789ABC'),
                };
            }

        ];
    }

    public function configure(): ContactFactory
    {
        return $this->afterMaking(function (Contact $contact) {
        })->afterCreating(function (Contact $contact) {

            if(!Arr::get($contact->data,'dev.forDependantPatient')){
                Address::factory()->count(1)->for(
                    $contact,
                    'owner'
                )->create();
            }


        });
    }


    public function forDependantPatient(): ContactFactory
    {
        return $this->state(function () {
            return [
                'date_of_birth'=>$this->faker->dateTimeBetween('-10 years','-6 months'),
                'email' => '',
                'phone'=>'',
                'data'=>['dev'=>['forDependantPatient'=>true]]
            ];
        });
    }


}
