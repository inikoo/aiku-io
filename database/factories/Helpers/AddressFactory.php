<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 28 Aug 2021 02:30:11 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace Database\Factories\Helpers;

use App\Models\Assets\Country;
use App\Models\Helpers\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{

    protected $model = Address::class;


    public function definition(): array
    {

        $country= match (config('app.faker_locale')) {
            'en_GB' => Country::firstWhere('code', 'GB'),
            'ms_MY' => Country::firstWhere('code', 'MY'),
            default => Country::firstWhere('code', 'US')
        };

        return [
            'address_line_1' => $this->faker->streetAddress(),
            'postal_code'    => $this->faker->postcode(),
            'locality'       => $this->faker->city(),
            'country_id'     => $country->id
        ];
    }

}
